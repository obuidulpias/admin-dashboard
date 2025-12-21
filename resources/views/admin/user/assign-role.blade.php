@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Assign Roles to {{ $user->name }}</h3>
                    </div>
                    <form action="{{ route('users.update-roles', $user->id) }}" method="POST">
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

                            <div class="alert alert-info">
                                <i class="fas fa-user"></i> <strong>User:</strong> {{ $user->name }} ({{ $user->email }})
                            </div>

                            <div class="form-group">
                                <label>Select Roles</label>
                                <div class="row">
                                    @forelse($roles as $role)
                                        <div class="col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" 
                                                    id="role{{ $role->id }}" 
                                                    name="roles[]" 
                                                    value="{{ $role->id }}"
                                                    {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="role{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted">No roles available. Please create roles first.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @if($user->roles->count() > 0)
                                <div class="alert alert-secondary">
                                    <strong>Current Roles:</strong>
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-info">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Roles
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
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

