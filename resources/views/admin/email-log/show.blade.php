@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Email Log Details</h3>
                        <div class="card-tools">
                            <a href="{{ route('email-logs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">ID:</dt>
                                    <dd class="col-sm-8">{{ $emailLog->id }}</dd>

                                    <dt class="col-sm-4">Email Type:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-info">
                                            {{ $emailLog->emailType->name ?? 'N/A' }}
                                        </span>
                                        @if($emailLog->emailType)
                                            <code class="ml-2">({{ $emailLog->emailType->constant }})</code>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Recipient:</dt>
                                    <dd class="col-sm-8">{{ $emailLog->to_email }}</dd>

                                    <dt class="col-sm-4">Status:</dt>
                                    <dd class="col-sm-8">
                                        @if($emailLog->status == 'sent')
                                            <span class="badge badge-success">Sent</span>
                                        @elseif($emailLog->status == 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Attempts:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-secondary">
                                            {{ $emailLog->attempts }} / {{ $emailLog->max_attempts }}
                                        </span>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">Created At:</dt>
                                    <dd class="col-sm-8">{{ $emailLog->created_at->format('Y-m-d H:i:s') }}</dd>

                                    <dt class="col-sm-4">Last Attempt:</dt>
                                    <dd class="col-sm-8">
                                        @if($emailLog->last_attempt_at)
                                            {{ $emailLog->last_attempt_at->format('Y-m-d H:i:s') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Sent At:</dt>
                                    <dd class="col-sm-8">
                                        @if($emailLog->sent_at)
                                            {{ $emailLog->sent_at->format('Y-m-d H:i:s') }}
                                        @else
                                            <span class="text-muted">Not sent yet</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Can Retry:</dt>
                                    <dd class="col-sm-8">
                                        @if($emailLog->canRetry())
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label><strong>Subject:</strong></label>
                            <div class="p-2 border rounded bg-light">
                                {{ $emailLog->subject }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Body:</strong></label>
                            <div class="p-3 border rounded" style="min-height: 300px; background-color: #f8f9fa;">
                                {!! $emailLog->body !!}
                            </div>
                        </div>

                        @if($emailLog->error)
                            <div class="alert alert-danger">
                                <strong><i class="fas fa-exclamation-triangle"></i> Error:</strong>
                                <pre class="mb-0 mt-2">{{ $emailLog->error }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
