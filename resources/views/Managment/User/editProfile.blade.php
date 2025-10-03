@extends('Template.template')

@section('container')
    <div class="container mt-4">
        <h3 class="mb-4">Edit User</h3>
            @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <form action="{{ route('user.update', Crypt::encrypt($user->id)) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required>
            <div class="mb-3">
                <label for="password" class="form-label">Password  </label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
