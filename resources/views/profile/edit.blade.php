@extends('layouts.app')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Profile</div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address"
                                   value="{{ old('address', $user->address) }}"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Profile Photo</label>
                            <input type="file" name="avatar" class="form-control-file">
                        </div>

                        <button class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <img
                        src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('admin/dist/img/user2-160x160.jpg') }}"
                        class="img-circle elevation-2 mb-3"
                        width="150"
                    >
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
