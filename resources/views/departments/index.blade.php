@extends('layouts.app')

@section('title', 'Kelola Departments')

@section('content')

    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Departments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Departments</li>
                    </ol>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-2 mt-4">
                <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Department</a>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead class="bg-dark text-white">
                    <tr style="text-align: center">
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $key => $department)
                        <tr>
                            <td style="text-align: center">{{ $loop->iteration }}</td>
                            <td>{{ $department['name'] }}</td>
                            <td style="text-align: center">{{ $department['code'] }}</td>
                            <td style="text-align: center">
                                <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline"
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
