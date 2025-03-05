@extends('layouts.app')

@section('title', 'Kelola Budeget Departments')

@section('content')

    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Budeget Departments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Budeget Departments</li>
                    </ol>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-2 mt-4">
                <a href="{{ route('budget-departments.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Budeget Department</a>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
                <table class="table table-bordered ">
                    <thead class="bg-dark text-white">
                    <tr style="text-align: center">
                        <th>#</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Used</th>
                        <th>Remaining</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($budgetDepartments as $budgetDepartment)
                    <tr>
                            <td style="text-align: center">{{ $loop->iteration }}</td>
                            <td style="text-align: center">{{ $budgetDepartment['code'] }}</td>
                            <td>{{ $budgetDepartment->department->name }}</td>
                            <td>{{ $budgetDepartment->name }}</td>
                            <td>{{ $budgetDepartment->amount_formatted }}</td>
                            <td>{{ $budgetDepartment->used_amount_formatted }}</td>
                            <td>{{ $budgetDepartment->remaining_amount_formatted }}</td>
                            <td>{{ $budgetDepartment->valid_from_formatted }}</td>
                            <td>{{ $budgetDepartment->valid_to_formatted }}</td>
                            <td>{{ $budgetDepartment->status_text }}</td>
                            <td>{{ $budgetDepartment->user->name }}</td>
                            <td style="text-align: center">
                                <a href="{{ route('budget-departments.edit', $budgetDepartment->id) }}" class="btn btn-warning btn-sm"><i
                                    class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('budget-departments.destroy', $budgetDepartment->id) }}" method="POST" class="d-inline"
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
