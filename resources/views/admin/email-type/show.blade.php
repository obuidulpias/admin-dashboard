@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Email Type Details</h3>
                        <div class="card-tools">
                            <a href="{{ route('email-types.edit', $emailType->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('email-types.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9">{{ $emailType->id }}</dd>

                            <dt class="col-sm-3">Name:</dt>
                            <dd class="col-sm-9">{{ $emailType->name }}</dd>

                            <dt class="col-sm-3">Constant:</dt>
                            <dd class="col-sm-9"><code>{{ $emailType->constant }}</code></dd>

                            <dt class="col-sm-3">Created At:</dt>
                            <dd class="col-sm-9">{{ $emailType->created_at->format('Y-m-d H:i:s') }}</dd>

                            <dt class="col-sm-3">Updated At:</dt>
                            <dd class="col-sm-9">{{ $emailType->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </dl>

                        <hr>

                        <h5>Templates ({{ $emailType->templates->count() }})</h5>
                        @if($emailType->templates->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Subject</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($emailType->templates as $template)
                                            <tr>
                                                <td>{{ $template->id }}</td>
                                                <td>{{ Str::limit($template->subject, 50) }}</td>
                                                <td>{{ $template->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('email-templates.edit', $template->id) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No templates found for this email type.</p>
                            <a href="{{ route('email-templates.create', ['email_type_id' => $emailType->id]) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Create Template
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

