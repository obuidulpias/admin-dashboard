@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Users</h3>
                        @can('user-create')
                        <div class="card-tools">
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add New User
                                </a>
                            </div>
                        @endcan
                    </div>
                    
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Filter Section -->
                        <div class="card mb-3" style="background-color: #f8f9fa;">
                            <div class="card-header" style="cursor: pointer; background-color: #e9ecef;" data-toggle="collapse" data-target="#filterOptions">
                                <h5 class="mb-0">
                                    <i class="fas fa-filter"></i> Filter Options
                                    <i class="fas fa-chevron-down float-right"></i>
                                </h5>
                            </div>
                            <div id="filterOptions" class="collapse">
                                <div class="card-body">
                                    <form method="GET" action="{{ route('users.index') }}" id="filterForm">
                                        <div class="row">
                                            <!-- User Id -->
                                            <div class="col-md-3 mb-3">
                                                <label for="user_id">User Id</label>
                                                <input type="text" class="form-control" id="user_id" name="user_id" 
                                                       value="{{ request('user_id') }}" placeholder="Enter User ID">
                                            </div>

                                            <!-- User Name -->
                                            <div class="col-md-3 mb-3">
                                                <label for="name">User Name</label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="{{ request('name') }}" placeholder="Enter Name">
                                            </div>

                                            <!-- User Email -->
                                            <div class="col-md-3 mb-3">
                                                <label for="email">User Email</label>
                                                <input type="text" class="form-control" id="email" name="email" 
                                                       value="{{ request('email') }}" placeholder="Enter Email">
                                            </div>

                                            <!-- Email Verified -->
                                            <div class="col-md-3 mb-3">
                                                <label for="email_verified">Email Verified</label>
                                                <select class="form-control" id="email_verified" name="email_verified">
                                                    <option value="">Select Status</option>
                                                    <option value="1" {{ request('email_verified') == '1' ? 'selected' : '' }}>Verified</option>
                                                    <option value="0" {{ request('email_verified') == '0' ? 'selected' : '' }}>Not Verified</option>
                                                </select>
                                            </div>

                                            <!-- Role -->
                                            <div class="col-md-3 mb-3">
                                                <label for="role">Role</label>
                                                <select class="form-control" id="role" name="role">
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Created Date -->
                                            <div class="col-md-3 mb-3">
                                                <label for="created_date">Created Date</label>
                                                <input type="date" class="form-control" id="created_date" name="created_date" 
                                                       value="{{ request('created_date') }}">
                                            </div>

                                            <!-- Order By -->
                                            <div class="col-md-3 mb-3">
                                                <label for="order_by">Order By</label>
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="">Select Order By</option>
                                                    <option value="id" {{ request('order_by') == 'id' ? 'selected' : '' }}>ID</option>
                                                    <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>Name</option>
                                                    <option value="email" {{ request('order_by') == 'email' ? 'selected' : '' }}>Email</option>
                                                    <option value="created_at" {{ request('order_by') == 'created_at' || !request('order_by') ? 'selected' : '' }}>Created Date</option>
                                                </select>
                                            </div>

                                            <!-- Paginate -->
                                            <div class="col-md-3 mb-3">
                                                <label for="per_page">Paginate</label>
                                                <select class="form-control" id="per_page" name="per_page">
                                                    <option value="">Select Pagination</option>
                                                    <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10</option>
                                                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Filter Buttons -->
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <button type="submit" name="download" value="1" class="btn btn-success ml-2">
                                                    <i class="fas fa-download"></i> Download
                                                </button>
                                                <a href="{{ route('users.index') }}" class="btn btn-danger ml-2">
                                                    <i class="fas fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Created At</th>
                                    <th style="width: 200px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->roles->count() > 0)
                                                @foreach($user->roles as $role)
                                                    <span class="badge badge-info">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No roles</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @can('user-edit')
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('user-assign-role')
                                            <a href="{{ route('users.assign-role', $user->id) }}" class="btn btn-warning btn-sm" title="Assign Roles">
                                                <i class="fas fa-user-tag"></i>
                                            </a>
                                            @endcan
                                            @can('user-delete')
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled title="Cannot delete yourself">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card-header[data-toggle="collapse"] {
        transition: background-color 0.3s ease;
    }
    .card-header[data-toggle="collapse"]:hover {
        background-color: #dee2e6 !important;
    }
    .card-header .fas.fa-chevron-down {
        transition: transform 0.3s ease;
    }
    .card-header[aria-expanded="true"] .fas.fa-chevron-down {
        transform: rotate(180deg);
    }
    #filterForm label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    #filterForm .form-control {
        font-size: 0.9rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle chevron icon on collapse
        $('#filterOptions').on('show.bs.collapse', function() {
            $('[data-target="#filterOptions"] .fa-chevron-down').addClass('rotate-180');
        });
        
        $('#filterOptions').on('hide.bs.collapse', function() {
            $('[data-target="#filterOptions"] .fa-chevron-down').removeClass('rotate-180');
        });
        
        // Show filter panel if any filter is active
        var hasFilters = {{ count(request()->except(['page', '_token'])) > 0 ? 'true' : 'false' }};
        if (hasFilters) {
            $('#filterOptions').collapse('show');
        }
    });
</script>
@endpush

