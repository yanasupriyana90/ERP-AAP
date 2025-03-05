@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-3">Purchase Requisitions</h4>
            <a href="{{ route('purchase-requisitions.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Tambah Purchase Requisition
            </a>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="prTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>PR Number</th>
                                <th>Tanggal PR</th>
                                <th>Department</th>
                                <th>Budget</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseRequisitions as $index => $pr)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pr->pr_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pr->pr_date)->format('d-m-Y') }}</td>
                                    <td>{{ $pr->department->name ?? '-' }}</td>
                                    <td>{{ $pr->budgetDepartment->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($pr->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($pr->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($pr->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase-requisitions.show', $pr->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('purchase-requisitions.edit', $pr->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('purchase-requisitions.destroy', $pr->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus PR ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#prTable').DataTable();
    });
</script>
@endsection
