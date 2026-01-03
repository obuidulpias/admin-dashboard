<?php

namespace App\Http\Controllers\AuditLog;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $auditLogs = $this->auditLogService->getFilteredLogs($request, $perPage);
        $modelTypes = $this->auditLogService->getModelTypes();

        return view('admin.audit-log.index', compact('auditLogs', 'modelTypes'));
    }

    /**
     * Display the specified audit log.
     */
    public function show($id)
    {
        $auditLog = AuditLog::with(['user', 'auditable'])->findOrFail($id);
        
        return view('admin.audit-log.show', compact('auditLog'));
    }
}
