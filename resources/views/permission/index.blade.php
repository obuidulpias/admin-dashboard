@extends('layouts.admin')

@section('main-content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Permissions</h3>
                        <div class="card-tools">
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Permission
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

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Permission Name</th>
                                    <th>Guard Name</th>
                                    <th>Created At</th>
                                    <th style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td><span class="badge badge-secondary">{{ $permission->guard_name }}</span></td>
                                        <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No permissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

