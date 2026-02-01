@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Email Template Details</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#testSendModal">
                                <i class="fas fa-paper-plane"></i> Test Send
                            </button>
                            <a href="{{ route('email-templates.edit', $template->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('email-templates.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9">{{ $template->id }}</dd>

                            <dt class="col-sm-3">Email Type:</dt>
                            <dd class="col-sm-9">
                                {{ $template->emailType->name }} 
                                <code class="ml-2">({{ $template->emailType->constant }})</code>
                            </dd>

                            <dt class="col-sm-3">Subject:</dt>
                            <dd class="col-sm-9">{{ $template->subject }}</dd>

                            <dt class="col-sm-3">Variables:</dt>
                            <dd class="col-sm-9">
                                @if($template->variables && count($template->variables) > 0)
                                    @foreach($template->variables as $var)
                                        <span class="badge badge-secondary">{{ $var }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">None detected</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Created At:</dt>
                            <dd class="col-sm-9">{{ $template->created_at->format('Y-m-d H:i:s') }}</dd>

                            <dt class="col-sm-3">Updated At:</dt>
                            <dd class="col-sm-9">{{ $template->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </dl>

                        <hr>

                        <h5>Body Preview</h5>
                        <div class="border p-3" style="min-height: 200px;">
                            {!! $template->body !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Test Send Modal -->
<div class="modal fade" id="testSendModal" tabindex="-1" role="dialog" aria-labelledby="testSendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testSendModalLabel">
                    <i class="fas fa-paper-plane"></i> Send Test Email
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('email-templates.test-send', $template->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="test_email">Test Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="test_email" id="test_email" 
                               class="form-control @error('test_email') is-invalid @enderror" 
                               placeholder="Enter test email address" 
                               value="{{ old('test_email', auth()->user()->email ?? '') }}" required>
                        @error('test_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">The test email will be sent to this address.</small>
                    </div>

                    @if($template->variables && count($template->variables) > 0)
                        <div class="form-group">
                            <label>Test Data for Variables</label>
                            <div class="alert alert-info">
                                <small>Enter test values for the template variables. Leave empty to use default test values.</small>
                            </div>
                            @foreach($template->variables as $var)
                                <div class="form-group mb-2">
                                    <label for="test_data_{{ $var }}">{{ ucfirst(str_replace('_', ' ', $var)) }}</label>
                                    <input type="text" 
                                           name="test_data[{{ $var }}]" 
                                           id="test_data_{{ $var }}" 
                                           class="form-control" 
                                           placeholder="Test {{ $var }} (default: Test {{ $var }})"
                                           value="{{ old("test_data.{$var}") }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <small>No variables detected in this template.</small>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
