@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Create New User</h3>
            <p class="text-muted mb-0">Add a new user to the system</p>
        </div>
        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Users
        </a>
    </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Username Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   placeholder="John Doe"
                                   value="{{ old('name') }}"
                                   required>
                            <label for="name">Username</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   placeholder="user@example.com"
                                   value="{{ old('email') }}"
                                   required>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Password"
                                   required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">Minimum 8 characters</small>
                        </div>
                    </div>
                    
                    <!-- Confirm Password Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm Password"
                            required>
                            <label for="password_confirmation">Confirm Password</label>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Level Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('level') is-invalid @enderror"
                                    id="level"
                                    name="level"
                                    required>
                                <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="operator" {{ old('level') == 'operator' ? 'selected' : '' }}>Operator</option>
                            </select>
                            <label for="level">User Role</label>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-user-plus me-1"></i> Create User
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2">
                        <i class="ti ti-reload me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
