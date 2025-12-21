<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $query = User::with('roles');

        // Apply filters
        if (request()->filled('user_id')) {
            $query->where('id', request('user_id'));
        }

        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if (request()->filled('email')) {
            $query->where('email', 'like', '%' . request('email') . '%');
        }

        if (request()->filled('email_verified')) {
            if (request('email_verified') == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        if (request()->filled('role')) {
            $query->whereHas('roles', function($q) {
                $q->where('id', request('role'));
            });
        }

        if (request()->filled('created_date')) {
            $query->whereDate('created_at', request('created_date'));
        }

        // Order By
        $orderBy = request('order_by', 'created_at');
        $orderDirection = request('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Check if download is requested
        if (request()->has('download')) {
            return $this->downloadUsers($query);
        }

        // Pagination
        $perPage = request('per_page', 10);
        $users = $query->paginate($perPage)->appends(request()->except('page'));

        // Get all roles for filter dropdown
        $roles = Role::all();

        return view('admin.user.index', compact('users', 'roles'));
    }

    /**
     * Download filtered users as CSV.
     */
    private function downloadUsers($query)
    {
        $users = $query->get();
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 
                'Name', 
                'Email', 
                'Email Verified', 
                'Roles', 
                'Created At'
            ]);
            
            // Add data rows
            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->implode(', ');
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->email_verified_at ? 'Yes' : 'No',
                    $roles ?: 'No roles',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign roles if provided
            if ($request->has('roles')) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
            }

            return redirect()->route('users.index')->with('success', 'User created successfully!');
        } catch (Exception $e) {
            return redirect()->route('users.create')
                ->withErrors(['error' => 'An error occurred while creating the user: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.user.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update roles if provided
            if ($request->has('roles')) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }

            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('users.edit', $id)
                ->withErrors(['error' => 'An error occurred while updating the user: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id == Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Show the form for assigning roles to user.
     */
    public function assignRole($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.user.assign-role', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update roles for the specified user.
     */
    public function updateRoles($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Get array of role ids or empty array if none
            $roleIds = request()->input('roles', []);

            // Sync roles - if roleIds is empty, all roles will be removed
            if (!empty($roleIds)) {
                $roles = Role::whereIn('id', $roleIds)->get();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }
            
            return redirect()->route('users.index')
                ->with('success', 'User roles updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('users.assign-role', $id)
                ->withErrors(['error' => 'An error occurred while updating user roles: ' . $e->getMessage()])
                ->withInput();
        }
    }
}

