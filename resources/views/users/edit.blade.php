@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit User</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('users.form', ['user' => $user])
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
