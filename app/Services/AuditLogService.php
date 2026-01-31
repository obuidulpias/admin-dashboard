<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Log an audit event.
     * 
     * @param string $event Event type (created, updated, deleted, etc.)
     * @param Model $model The model being audited
     * @param array|null $oldValues Old values (for updates/deletes)
     * @param array|null $newValues New values (for creates/updates)
     */
    public function log(string $event, Model $model, ?array $oldValues = null, ?array $newValues = null): void
    {
        // Skip if this is the AuditLog model itself to avoid infinite recursion
        if ($model instanceof AuditLog) {
            return;
        }

        try {
            $user = Auth::user();
            
            // Filter out sensitive fields
            $hiddenFields = ['password', 'remember_token'];
            
            if ($oldValues) {
                $oldValues = array_diff_key($oldValues, array_flip($hiddenFields));
            }
            
            if ($newValues) {
                $newValues = array_diff_key($newValues, array_flip($hiddenFields));
            }

            AuditLog::create([
                'user_type' => $user ? get_class($user) : null,
                'user_id' => $user ? $user->id : null,
                'event' => $event,
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail to prevent breaking the application
            Log::error('Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Get filtered audit logs with pagination.
     * 
     * @param Request $request
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilteredLogs(Request $request, int $perPage = 25)
    {
        $query = AuditLog::with(['user', 'auditable']);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', 'like', '%' . $request->event . '%');
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Order By
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Pagination
        return $query->paginate($perPage)->appends($request->except('page'));
    }

    /**
     * Get unique model types for filter dropdown.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getModelTypes()
    {
        return AuditLog::select('auditable_type')
            ->distinct()
            ->orderBy('auditable_type')
            ->pluck('auditable_type')
            ->unique()
            ->values();
    }
}

