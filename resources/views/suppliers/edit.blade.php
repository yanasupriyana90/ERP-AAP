@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Supplier</h2>

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Kode Supplier</label>
            <input type="text" class="form-control" value="{{ $supplier->code }}" disabled>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" class="form-control">{{ $supplier->address }}</textarea>
        </div>

        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
        </div>

        <div class="form-group">
            <label>Kontak Person</label>
            <input type="text" name="contact_person" class="form-control" value="{{ $supplier->contact_person }}">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>
@endsection
