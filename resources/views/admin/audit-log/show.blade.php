@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-eye"></i> Audit Log Details
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('audit-logs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px;">ID</th>
                                        <td>{{ $auditLog->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>User</th>
                                        <td>
                                            @if($auditLog->user)
                                                <strong>{{ $auditLog->user->name }}</strong><br>
                                                <small class="text-muted">{{ $auditLog->user->email }}</small>
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Event</th>
                                        <td>
                                            @if($auditLog->event == 'created')
                                                <span class="badge badge-success">{{ ucfirst($auditLog->event) }}</span>
                                            @elseif($auditLog->event == 'updated')
                                                <span class="badge badge-info">{{ ucfirst($auditLog->event) }}</span>
                                            @elseif($auditLog->event == 'deleted')
                                                <span class="badge badge-danger">{{ ucfirst($auditLog->event) }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ ucfirst($auditLog->event) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Model Type</th>
                                        <td><strong>{{ $auditLog->auditable_type }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Model ID</th>
                                        <td>{{ $auditLog->auditable_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>IP Address</th>
                                        <td>{{ $auditLog->ip_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>User Agent</th>
                                        <td><small>{{ $auditLog->user_agent ?? 'N/A' }}</small></td>
                                    </tr>
                                    <tr>
                                        <th>URL</th>
                                        <td><small>{{ $auditLog->url ?? 'N/A' }}</small></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>
                                            {{ $auditLog->created_at->format('M d, Y H:i:s') }}<br>
                                            <small class="text-muted">{{ $auditLog->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Old Values</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        @if($auditLog->old_values)
                                            <pre style="max-height: 300px; overflow-y: auto; font-size: 0.85rem;">{{ json_encode(json_decode($auditLog->old_values), JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            <p class="text-muted mb-0">No old values</p>
                                        @endif
                                    </div>
                                </div>

                                <h5 class="mt-3">New Values</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        @if($auditLog->new_values)
                                            <pre style="max-height: 300px; overflow-y: auto; font-size: 0.85rem;">{{ json_encode(json_decode($auditLog->new_values), JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            <p class="text-muted mb-0">No new values</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

