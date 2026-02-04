<?php

namespace App\Http\Controllers\EmailType;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailType\EmailTypeStoreRequest;
use App\Http\Requests\EmailType\EmailTypeUpdateRequest;
use App\Models\EmailType;
use Illuminate\Http\Request;

class EmailTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emailTypes = EmailType::withCount('templates')->latest()->paginate(15);
        return view('admin.email-type.index', compact('emailTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.email-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTypeStoreRequest $request)
    {
        try {
            EmailType::create([
                'name' => $request->name,
                'constant' => $request->constant,
            ]);

            return redirect()->route('email-types.index')
                ->with('success', 'Email type created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('email-types.create')
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $emailType = EmailType::with('templates')->findOrFail($id);
        return view('admin.email-type.show', compact('emailType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $emailType = EmailType::findOrFail($id);
        return view('admin.email-type.edit', compact('emailType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTypeUpdateRequest $request, string $id)
    {
        try {
            $emailType = EmailType::findOrFail($id);
            $emailType->update([
                'name' => $request->name,
                'constant' => $request->constant,
            ]);

            return redirect()->route('email-types.index')
                ->with('success', 'Email type updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('email-types.edit', $id)
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
            $emailType = EmailType::findOrFail($id);
            
            // Check if email type has templates
            if ($emailType->templates()->count() > 0) {
                return redirect()->route('email-types.index')
                    ->with('error', 'Cannot delete email type with existing templates!');
            }

            $emailType->delete();

            return redirect()->route('email-types.index')
                ->with('success', 'Email type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('email-types.index')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
