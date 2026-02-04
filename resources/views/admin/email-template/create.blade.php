@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add New Email Template</h3>
                    </div>
                    <form action="{{ route('email-templates.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
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
                                <label for="email_type_id">Email Type <span class="text-danger">*</span></label>
                                <select name="email_type_id" id="email_type_id" 
                                    class="form-control @error('email_type_id') is-invalid @enderror" required>
                                    <option value="">Select Email Type</option>
                                    @foreach($emailTypes as $type)
                                        <option value="{{ $type->id }}" 
                                            {{ old('email_type_id', request('email_type_id', $selectedEmailTypeId ?? '')) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->constant }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('email_type_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject"
                                    class="form-control @error('subject') is-invalid @enderror" id="subject"
                                    placeholder="e.g., Welcome @{{name}} to FundedNext" 
                                    value="{{ old('subject') }}" required>
                                <small class="form-text text-muted">Use @{{variable_name}} for dynamic variables</small>
                                @error('subject')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="body">Body <span class="text-danger">*</span></label>
                                <textarea name="body" id="body" 
                                    class="form-control @error('body') is-invalid @enderror" 
                                    rows="15" required>{{ old('body') }}</textarea>
                                <small class="form-text text-muted">Use @{{variable_name}} for dynamic variables. HTML is supported.</small>
                                @error('body')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <strong>Available Variables:</strong> Variables will be automatically extracted from your template. 
                                Use the format <code>@{{variable_name}}</code> in both subject and body.
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Template
                            </button>
                            <a href="{{ route('email-templates.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Summernote WYSIWYG editor
        $('#body').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onInit: function() {
                    // Add placeholder text
                    $(this).summernote('code', '<p>Enter your email body here. Use @{{variable_name}} for dynamic variables.</p>');
                }
            }
        });
    });
</script>
@endsection

