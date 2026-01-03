<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\AuditLogService;
use Spatie\Permission\Models\Permission;
use Exception;

class PermissionController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $query = Permission::query();

        // Filter by Permission ID
        if (request()->has('permission_id') && request('permission_id') != '') {
            $query->where('id', request('permission_id'));
        }

        // Filter by Permission Name
        if (request()->has('name') && request('name') != '') {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        // Filter by Guard Name
        if (request()->has('guard_name') && request('guard_name') != '') {
            $query->where('guard_name', request('guard_name'));
        }

        // Filter by Created Date
        if (request()->has('created_date') && request('created_date') != '') {
            $query->whereDate('created_at', request('created_date'));
        }

        // Order By
        $orderBy = request('order_by', 'created_at');
        $query->orderBy($orderBy, 'desc');

        // Pagination
        $perPage = request('per_page', 15);
        $permissions = $query->paginate($perPage)->appends(request()->except('page'));

        return view('permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        return view('permission.create');
    }

    /**
     * Store a newly created permission.
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $permission = Permission::create(['name' => $request->name]);
            
            // Log the creation
            $this->auditLogService->log('permission created', $permission, null, $permission->getAttributes());
            
            return redirect()->route('permissions.index')
                ->with('success', 'Permission created successfully!');
        } catch (Exception $e) {
            return redirect()->route('permissions.create')
                ->withErrors(['error' => 'An error occurred while creating the permission: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified permission.
     */
    public function show($id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        return view('permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permission.edit', compact('permission'));
    }

    /**
     * Update the specified permission.
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            // Get old values before update
            $oldValues = $permission->getAttributes();
            
            // Update the permission
            $permission->update(['name' => $request->name]);
            
            // Log the update
            $this->auditLogService->log('permission updated', $permission, $oldValues, $permission->getChanges());
            
            return redirect()->route('permissions.index')
                ->with('success', 'Permission updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('permissions.edit', $id)
                ->withErrors(['error' => 'An error occurred while updating the permission: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified permission.
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            // Check if permission is assigned to any roles
            if ($permission->roles()->count() > 0) {
                return redirect()->route('permissions.index')
                    ->with('error', 'Cannot delete permission assigned to roles!');
            }
            
            // Log the deletion before deleting
            $this->auditLogService->log('permission deleted', $permission, $permission->getAttributes(), null);
            
            $permission->delete();
            
            return redirect()->route('permissions.index')
                ->with('success', 'Permission deleted successfully!');
        } catch (Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'An error occurred while deleting the permission: ' . $e->getMessage());
        }
    }
}

