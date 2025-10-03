@extends('Template.template')

@section('container')
<div class="container mt-4">
    <h3 class="mb-4">Edit Group</h3>
    <form action="{{ route('group.update', Crypt::encrypt($group->id)) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Group</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $group->nama) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('group.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
