@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Unit</h2>

    <form action="{{ route('units.update', $unit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ $unit->code }}" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $unit->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
