@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i> Audit Logs
                        </h3>
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
                                    <form method="GET" action="{{ route('audit-logs.index') }}" id="filterForm">
                                        <div class="row">
                                            <!-- User ID -->
                                            <div class="col-md-3 mb-3">
                                                <label for="user_id">User ID</label>
                                                <input type="text" class="form-control" id="user_id" name="user_id" 
                                                       value="{{ request('user_id') }}" placeholder="Enter User ID">
                                            </div>

                                            <!-- Event -->
                                            <div class="col-md-3 mb-3">
                                                <label for="event">Event</label>
                                                <input type="text" class="form-control" id="event" name="event" 
                                                       value="{{ request('event') }}" placeholder="Enter event name (e.g., created, updated, deleted)">
                                            </div>

                                            <!-- Model Type -->
                                            <div class="col-md-3 mb-3">
                                                <label for="auditable_type">Model Type</label>
                                                <select class="form-control" id="auditable_type" name="auditable_type">
                                                    <option value="">All Models</option>
                                                    @foreach($modelTypes as $type)
                                                        <option value="{{ $type }}" {{ request('auditable_type') == $type ? 'selected' : '' }}>
                                                            {{ class_basename($type) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Date From -->
                                            <div class="col-md-3 mb-3">
                                                <label for="date_from">Date From</label>
                                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                                       value="{{ request('date_from') }}">
                                            </div>

                                            <!-- Date To -->
                                            <div class="col-md-3 mb-3">
                                                <label for="date_to">Date To</label>
                                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                                       value="{{ request('date_to') }}">
                                            </div>

                                            <!-- Order By -->
                                            <div class="col-md-3 mb-3">
                                                <label for="order_by">Order By</label>
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="created_at" {{ request('order_by') == 'created_at' || !request('order_by') ? 'selected' : '' }}>Created Date</option>
                                                    <option value="event" {{ request('order_by') == 'event' ? 'selected' : '' }}>Event</option>
                                                    <option value="id" {{ request('order_by') == 'id' ? 'selected' : '' }}>ID</option>
                                                </select>
                                            </div>

                                            <!-- Order Direction -->
                                            <div class="col-md-3 mb-3">
                                                <label for="order_direction">Order Direction</label>
                                                <select class="form-control" id="order_direction" name="order_direction">
                                                    <option value="desc" {{ request('order_direction') == 'desc' || !request('order_direction') ? 'selected' : '' }}>Descending</option>
                                                    <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                                </select>
                                            </div>

                                            <!-- Paginate -->
                                            <div class="col-md-3 mb-3">
                                                <label for="per_page">Per Page</label>
                                                <select class="form-control" id="per_page" name="per_page">
                                                    <option value="25" {{ request('per_page') == '25' || !request('per_page') ? 'selected' : '' }}>25</option>
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
                                                <a href="{{ route('audit-logs.index') }}" class="btn btn-danger ml-2">
                                                    <i class="fas fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>User</th>
                                        <th>Event</th>
                                        <th>Model</th>
                                        <th>Model ID</th>
                                        <th>IP Address</th>
                                        <th>Date & Time</th>
                                        <th style="width: 100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($auditLogs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>
                                                @if($log->user)
                                                    <strong>{{ $log->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $log->user->email }}</small>
                                                @else
                                                    <span class="text-muted">System</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->event == 'created')
                                                    <span class="badge badge-success">{{ ucfirst($log->event) }}</span>
                                                @elseif($log->event == 'updated')
                                                    <span class="badge badge-info">{{ ucfirst($log->event) }}</span>
                                                @elseif($log->event == 'deleted')
                                                    <span class="badge badge-danger">{{ ucfirst($log->event) }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ ucfirst($log->event) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ class_basename($log->auditable_type) }}</strong>
                                            </td>
                                            <td>{{ $log->auditable_id }}</td>
                                            <td>
                                                <small>{{ $log->ip_address ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                {{ $log->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('audit-logs.show', $log->id) }}" class="btn btn-info btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No audit logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $auditLogs->links('pagination::bootstrap-4') }}
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
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
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

