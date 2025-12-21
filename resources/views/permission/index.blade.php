@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Permissions</h3>
                        <div class="card-tools">
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Permission
                            </a>
                        </div>
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
                                    <form method="GET" action="{{ route('permissions.index') }}" id="filterForm">
                                        <div class="row">
                                            <!-- Permission Id -->
                                            <div class="col-md-3 mb-3">
                                                <label for="permission_id">Permission ID</label>
                                                <input type="text" class="form-control" id="permission_id" name="permission_id" 
                                                       value="{{ request('permission_id') }}" placeholder="Enter Permission ID">
                                            </div>

                                            <!-- Permission Name -->
                                            <div class="col-md-3 mb-3">
                                                <label for="name">Permission Name</label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="{{ request('name') }}" placeholder="Enter Permission Name">
                                            </div>

                                            <!-- Guard Name -->
                                            <div class="col-md-3 mb-3">
                                                <label for="guard_name">Guard Name</label>
                                                <select class="form-control" id="guard_name" name="guard_name">
                                                    <option value="">Select Guard</option>
                                                    <option value="web" {{ request('guard_name') == 'web' ? 'selected' : '' }}>Web</option>
                                                    <option value="api" {{ request('guard_name') == 'api' ? 'selected' : '' }}>API</option>
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
                                                    <option value="guard_name" {{ request('order_by') == 'guard_name' ? 'selected' : '' }}>Guard Name</option>
                                                    <option value="created_at" {{ request('order_by') == 'created_at' || !request('order_by') ? 'selected' : '' }}>Created Date</option>
                                                </select>
                                            </div>

                                            <!-- Paginate -->
                                            <div class="col-md-3 mb-3">
                                                <label for="per_page">Paginate</label>
                                                <select class="form-control" id="per_page" name="per_page">
                                                    <option value="">Select Pagination</option>
                                                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                                    <option value="15" {{ request('per_page') == '15' || !request('per_page') ? 'selected' : '' }}>15</option>
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
                                                <a href="{{ route('permissions.index') }}" class="btn btn-danger ml-2">
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
                                    <th>Permission Name</th>
                                    <th>Guard Name</th>
                                    <th>Created At</th>
                                    <th style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td><span class="badge badge-secondary">{{ $permission->guard_name }}</span></td>
                                        <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No permissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $permissions->links() }}
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

