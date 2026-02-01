<?php

namespace App\Http\Controllers\EmailTemplate;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplate\EmailTemplateStoreRequest;
use App\Http\Requests\EmailTemplate\EmailTemplateUpdateRequest;
use App\Models\EmailTemplate;
use App\Models\EmailType;
use App\Services\CentralEmailService;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::with('emailType')->latest();

        // Filter by email type if provided
        if ($request->has('email_type_id') && $request->email_type_id) {
            $query->where('email_type_id', $request->email_type_id);
        }

        $templates = $query->paginate(15);
        $emailTypes = EmailType::all();

        return view('admin.email-template.index', compact('templates', 'emailTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $emailTypes = EmailType::all();
        $selectedEmailTypeId = $request->get('email_type_id');
        return view('admin.email-template.create', compact('emailTypes', 'selectedEmailTypeId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTemplateStoreRequest $request)
    {
        try {
            // Extract variables from body if not provided
            $variables = $request->variables;
            if (empty($variables) && $request->body) {
                $variables = CentralEmailService::extractVariables($request->body);
            }

            EmailTemplate::create([
                'email_type_id' => $request->email_type_id,
                'subject' => $request->subject,
                'body' => $request->body,
                'variables' => $variables,
            ]);

            return redirect()->route('email-templates.index')
                ->with('success', 'Email template created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('email-templates.create')
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = EmailTemplate::with('emailType')->findOrFail($id);
        return view('admin.email-template.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = EmailTemplate::findOrFail($id);
        $emailTypes = EmailType::all();
        return view('admin.email-template.edit', compact('template', 'emailTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTemplateUpdateRequest $request, string $id)
    {
        try {
            $template = EmailTemplate::findOrFail($id);

            // Extract variables from body if not provided
            $variables = $request->variables;
            if (empty($variables) && $request->body) {
                $variables = CentralEmailService::extractVariables($request->body);
            }

            $template->update([
                'email_type_id' => $request->email_type_id,
                'subject' => $request->subject,
                'body' => $request->body,
                'variables' => $variables,
            ]);

            return redirect()->route('email-templates.index')
                ->with('success', 'Email template updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('email-templates.edit', $id)
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $template = EmailTemplate::findOrFail($id);
            $template->delete();

            return redirect()->route('email-templates.index')
                ->with('success', 'Email template deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('email-templates.index')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Send a test email using this template.
     */
    public function testSend(Request $request, string $id)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $template = EmailTemplate::with('emailType')->findOrFail($id);
            
            // Prepare test data for variables
            $testData = [];
            if ($template->variables && count($template->variables) > 0) {
                foreach ($template->variables as $var) {
                    // Use test values for each variable
                    $testData[$var] = $request->input("test_data.{$var}", "Test {$var}");
                }
            }

            // Send test email using CentralEmailJob
            $details = [
                'email' => $request->test_email,
                'templateName' => $template->emailType->constant,
                'data' => $testData,
                'link' => [],
            ];

            \App\Jobs\CentralEmailJob::dispatch($details)
                ->onQueue(\App\Constants\AppConstants::QUEUE_DEFAULT_JOB);

            return redirect()->route('email-templates.show', $id)
                ->with('success', 'Test email queued successfully! Check your inbox at ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->route('email-templates.show', $id)
                ->with('error', 'An error occurred while sending test email: ' . $e->getMessage());
        }
    }
}
