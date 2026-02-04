@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-envelope"></i> Email Logs
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
                                    <form method="GET" action="{{ route('email-logs.index') }}" id="filterForm">
                                        <div class="row">
                                            <!-- Status -->
                                            <div class="col-md-3 mb-3">
                                                <label for="status">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">All Statuses</option>
                                                    @foreach($statuses as $status)
                                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Email Type -->
                                            <div class="col-md-3 mb-3">
                                                <label for="email_type_id">Email Type</label>
                                                <select class="form-control" id="email_type_id" name="email_type_id">
                                                    <option value="">All Email Types</option>
                                                    @foreach($emailTypes as $type)
                                                        <option value="{{ $type->id }}" {{ request('email_type_id') == $type->id ? 'selected' : '' }}>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Recipient Email -->
                                            <div class="col-md-3 mb-3">
                                                <label for="to_email">Recipient Email</label>
                                                <input type="text" class="form-control" id="to_email" name="to_email" 
                                                       value="{{ request('to_email') }}" placeholder="Enter email address">
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
                                                    <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>Created At</option>
                                                    <option value="sent_at" {{ request('order_by') == 'sent_at' ? 'selected' : '' }}>Sent At</option>
                                                    <option value="to_email" {{ request('order_by') == 'to_email' ? 'selected' : '' }}>Recipient</option>
                                                </select>
                                            </div>

                                            <!-- Order Direction -->
                                            <div class="col-md-3 mb-3">
                                                <label for="order_direction">Order Direction</label>
                                                <select class="form-control" id="order_direction" name="order_direction">
                                                    <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                                    <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                                </select>
                                            </div>

                                            <!-- Per Page -->
                                            <div class="col-md-3 mb-3">
                                                <label for="per_page">Per Page</label>
                                                <select class="form-control" id="per_page" name="per_page">
                                                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter"></i> Apply Filters
                                                </button>
                                                <a href="{{ route('email-logs.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Email Logs Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email Type</th>
                                        <th>Recipient</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Attempts</th>
                                        <th>Created At</th>
                                        <th>Sent At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emailLogs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $log->emailType->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $log->to_email }}</td>
                                            <td>{{ Str::limit($log->subject, 50) }}</td>
                                            <td>
                                                @if($log->status == 'sent')
                                                    <span class="badge badge-success">Sent</span>
                                                @elseif($log->status == 'failed')
                                                    <span class="badge badge-danger">Failed</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $log->attempts }}/{{ $log->max_attempts }}
                                                </span>
                                            </td>
                                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                @if($log->sent_at)
                                                    {{ $log->sent_at->format('Y-m-d H:i') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('email-logs.show', $log->id) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No email logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $emailLogs->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
