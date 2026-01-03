<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\AuditLogService;
use App\Services\UserService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $auditLogService;
    protected $userService;

    public function __construct(AuditLogService $auditLogService, UserService $userService)
    {
        $this->auditLogService = $auditLogService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Check if download is requested
        if ($request->has('download')) {
            $query = $this->userService->getFilteredUsersQuery($request);
            return $this->userService->downloadUsers($query);
        }

        // Get paginated users
        $perPage = $request->get('per_page', 10);
        $users = $this->userService->getFilteredUsers($request, $perPage);

        // Get all roles for filter dropdown
        $roles = Role::all();

        return view('admin.user.index', compact('users', 'roles'));
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

            // Log the creation
            $this->auditLogService->log('user created', $user, null, $user->getAttributes());

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
            // Get old values before update
            $oldValues = $user->getAttributes();
            $oldRoleIds = $user->roles->pluck('id')->sort()->values()->toArray();

            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update roles if provided
            $newRoleIds = [];
            if ($request->has('roles')) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
                $newRoleIds = $request->roles;
            } else {
                $user->syncRoles([]);
            }
            sort($newRoleIds);

            // Prepare new values with role changes
            $newValues = $user->getChanges();
            if ($oldRoleIds !== $newRoleIds) {
                $newValues['roles'] = $newRoleIds;
                $oldValues['roles'] = $oldRoleIds;
            }

            // Log the update
            $this->auditLogService->log('user updated', $user, $oldValues, $newValues);

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

        // Log the deletion before deleting
        $this->auditLogService->log('user deleted', $user, $user->getAttributes(), null);

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
            
            // Get old role IDs before update
            $oldRoleIds = $user->roles->pluck('id')->sort()->values()->toArray();
            
            // Get array of role ids or empty array if none
            $roleIds = request()->input('roles', []);
            $newRoleIds = !empty($roleIds) ? $roleIds : [];
            sort($newRoleIds);

            // Sync roles - if roleIds is empty, all roles will be removed
            if (!empty($roleIds)) {
                $roles = Role::whereIn('id', $roleIds)->get();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }
            
            // Log role assignment change
            if ($oldRoleIds !== $newRoleIds) {
                $this->auditLogService->log('user roles updated', $user, ['roles' => $oldRoleIds], ['roles' => $newRoleIds]);
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

