<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use Spatie\Permission\Models\Permission;
use Exception;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(15);
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
            Permission::create(['name' => $request->name]);
            
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
            $permission->update(['name' => $request->name]);
            
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
            
            $permission->delete();
            
            return redirect()->route('permissions.index')
                ->with('success', 'Permission deleted successfully!');
        } catch (Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'An error occurred while deleting the permission: ' . $e->getMessage());
        }
    }
}

