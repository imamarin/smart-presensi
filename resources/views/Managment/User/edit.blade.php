@extends('Template.template')

@section('container')
    <div class="container mt-4">
        <h3 class="mb-4">Edit User</h3>
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
                <label for="level" class="form-label">Level</label>
                <select class="form-select" id="level" name="level" required>
                    <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operator" {{ $user->level == 'operator' ? 'selected' : '' }}>Operator</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
