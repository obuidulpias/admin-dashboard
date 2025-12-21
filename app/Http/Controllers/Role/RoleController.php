<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('created_at', 'desc')->paginate(10);
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('role.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            
            return redirect()->route('roles.index')
                ->with('success', 'Role created successfully!');
        } catch (Exception $e) {
            return redirect()->route('roles.create')
                ->withErrors(['error' => 'An error occurred while creating the role: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => $request->name]);
            
            // Get array of permission ids or empty array if none
            $permissionIds = $request->input('permissions', []);

            // Your error is likely because Spatie's `syncPermissions` expects either permission names or Permission model instances.
            // If you pass IDs, you must first fetch the Permission models by those IDs.
            // The following will fetch the Permission models for the given IDs:
            if (!empty($permissionIds)) {
                $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('roles.edit', $id)
                ->withErrors(['error' => 'An error occurred while updating the role: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Prevent deleting if users are assigned to this role
            if ($role->users()->count() > 0) {
                return redirect()->route('roles.index')
                    ->with('error', 'Cannot delete role with assigned users!');
            }
            
            $role->delete();
            
            return redirect()->route('roles.index')
                ->with('success', 'Role deleted successfully!');
        } catch (Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'An error occurred while deleting the role: ' . $e->getMessage());
        }
    }
}

