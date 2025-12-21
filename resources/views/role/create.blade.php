@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add New Role</h3>
                    </div>
                    <form action="{{ route('roles.store') }}" method="POST">
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
                                <label for="name">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter role name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="d-flex align-items-center mb-3">
                                    <label class="mb-0 mr-2" style="white-space:nowrap;">Permissions</label>
                                    <input
                                        type="text"
                                        class="form-control ml-auto"
                                        id="permission-search"
                                        style="max-width: 250px;"
                                        placeholder="Search permissions...">
                                </div>
                                <div class="row" id="permissions-list">
                                    @forelse($permissions as $permission)
                                        <div class="col-md-4 permission-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" 
                                                    id="permission{{ $permission->id }}" 
                                                    name="permissions[]" 
                                                    value="{{ $permission->id }}"
                                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="permission{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted">No permissions available.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Role
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('permission-search');
        var permissionsList = document.getElementById('permissions-list');
        if (searchInput && permissionsList) {
            searchInput.addEventListener('input', function() {
                var filter = searchInput.value.toLowerCase();
                var items = permissionsList.querySelectorAll('.permission-item');
                items.forEach(function(item) {
                    var label = item.querySelector('label');
                    if (label) {
                        var text = label.innerText.toLowerCase();
                        if (text.indexOf(filter) > -1) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        }
    });
</script>
@endsection

