@extends('layouts.admin')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Email Type</h3>
                    </div>
                    <form action="{{ route('email-types.update', $emailType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
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
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter email type name" value="{{ old('name', $emailType->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="constant">Constant <span class="text-danger">*</span></label>
                                <input type="text" name="constant"
                                    class="form-control @error('constant') is-invalid @enderror" id="constant"
                                    placeholder="e.g., WELCOME_EMAIL" value="{{ old('constant', $emailType->constant) }}" required>
                                <small class="form-text text-muted">Use uppercase with underscores (e.g., WELCOME_EMAIL, OTP_VERIFICATION)</small>
                                @error('constant')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Email Type
                            </button>
                            <a href="{{ route('email-types.index') }}" class="btn btn-secondary">
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

