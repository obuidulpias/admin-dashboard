<?php

namespace App\Http\Controllers\EmailLog;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\EmailType;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the email logs.
     */
    public function index(Request $request)
    {
        $query = EmailLog::with('emailType')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by email type
        if ($request->filled('email_type_id')) {
            $query->where('email_type_id', $request->email_type_id);
        }

        // Filter by recipient email
        if ($request->filled('to_email')) {
            $query->where('to_email', 'like', '%' . $request->to_email . '%');
        }

        // Filter by date from
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter by date to
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Order by
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        $perPage = $request->get('per_page', 25);
        $emailLogs = $query->paginate($perPage)->appends($request->except('page'));

        $emailTypes = EmailType::all();
        $statuses = ['pending', 'sent', 'failed'];

        return view('admin.email-log.index', compact('emailLogs', 'emailTypes', 'statuses'));
    }

    /**
     * Display the specified email log.
     */
    public function show($id)
    {
        $emailLog = EmailLog::with('emailType')->findOrFail($id);
        return view('admin.email-log.show', compact('emailLog'));
    }
}
