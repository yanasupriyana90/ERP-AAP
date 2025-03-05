@extends('layouts.app')

@section('title', 'Tambah Department')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Department</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Department</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="code">Code Department</label>
                <input type="text" name="code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
