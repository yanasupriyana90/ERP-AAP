@extends('layouts.app')

@section('title', 'Kelola Units')

@section('content')

    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Units</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Units</li>
                    </ol>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-2 mt-4">
                <a href="{{ route('units.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah unit</a>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead class="bg-dark text-white">
                    <tr style="text-align: center">
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td style="text-align: center">{{ $loop->iteration }}</td>
                            <td style="text-align: center">{{ $unit['code'] }}</td>
                            <td>{{ $unit['name'] }}</td>
                            <td style="text-align: center">
                                <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                        Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
