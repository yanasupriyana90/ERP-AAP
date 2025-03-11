@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Daftar Persetujuan Purchase Order</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>PO Number</th>
                <th>Department</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($approvals as $index => $approval)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $approval->purchaseOrder->po_number }}</td>
                    <td>{{ $approval->purchaseOrder->department->name ?? '-' }}</td>
                    <td>{{ $approval->purchaseOrder->budgetDepartment->name ?? '-' }}</td>
                    <td>
                        @if ($approval->status == 0)
                            <span class="badge bg-warning">Pending</span>
                        @elseif ($approval->status == 1)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if ($approval->status == 0)
                            <form action="{{ route('po-approvals.approve', $approval->po_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('po-approvals.reject', $approval->po_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
