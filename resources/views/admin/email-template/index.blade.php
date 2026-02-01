@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Email Templates</h3>
                        <div class="card-tools">
                            <a href="{{ route('email-templates.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Template
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

                        <!-- Filter -->
                        <form method="GET" action="{{ route('email-templates.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="email_type_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Email Types</option>
                                        @foreach($emailTypes as $type)
                                            <option value="{{ $type->id }}" {{ request('email_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email Type</th>
                                    <th>Subject</th>
                                    <th>Variables</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                    <tr>
                                        <td>{{ $template->id }}</td>
                                        <td>{{ $template->emailType->name }}</td>
                                        <td>{{ Str::limit($template->subject, 50) }}</td>
                                        <td>
                                            @if($template->variables && count($template->variables) > 0)
                                                @foreach($template->variables as $var)
                                                    <span class="badge badge-secondary">{{ $var }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>{{ $template->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('email-templates.show', $template->id) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('email-templates.edit', $template->id) }}" 
                                               class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('email-templates.destroy', $template->id) }}" 
                                                  method="POST" style="display: inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No email templates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $templates->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

