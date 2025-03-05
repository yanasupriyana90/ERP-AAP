@extends('layouts.app')

@section('title', 'Edit Budget Department')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Budget Department</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('budget-departments.update', $budgetDepartment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_id" value="{{ $budgetDepartment->user_id }}">

            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                    <option value="">-- Pilih Department --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ $budgetDepartment->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Kode Budget</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ $budgetDepartment->code }}" disabled>
                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Nama Budget</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $budgetDepartment->name }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Jumlah Budget</label>
                <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ $budgetDepartment->amount }}">
                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Tanggal Berlaku</label>
                <input type="date" name="valid_from" class="form-control @error('valid_from') is-invalid @enderror" value="{{ $budgetDepartment->valid_from }}">
                @error('valid_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Tanggal Berakhir</label>
                <input type="date" name="valid_to" class="form-control @error('valid_to') is-invalid @enderror" value="{{ $budgetDepartment->valid_to }}">
                @error('valid_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="0" {{ $budgetDepartment->status == 0 ? 'selected' : '' }}>Aktif</option>
                    <option value="1" {{ $budgetDepartment->status == 1 ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('budget-departments.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
