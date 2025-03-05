@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Supplier</h2>

    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Kode Supplier</label>
            <input type="text" class="form-control" value="(Otomatis)" disabled>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="form-group">
            <label>Kontak Person</label>
            <input type="text" name="contact_person" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>
@endsection
