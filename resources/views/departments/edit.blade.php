@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Department</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="name">Nama Department</label>
                <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
            </div>
            <div class="form-group">
                <label for="code">Code Department</label>
                <input type="text" name="code" class="form-control" value="{{ $department->code }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
