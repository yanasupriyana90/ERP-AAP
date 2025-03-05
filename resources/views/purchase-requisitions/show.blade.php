@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Detail Purchase Requisition</h4>
            <a href="{{ route('purchase-requisitions.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="printPage()" class="btn btn-success mb-3">
                <i class="fas fa-print"></i> Print
            </button>

            <div class="card" id="printSection">
                <div class="card-body">
                    <h5>Purchase Requisition</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>PR Number</th>
                            <td>{{ $purchaseRequisition->pr_number }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal PR</th>
                            <td>{{ \Carbon\Carbon::parse($purchaseRequisition->pr_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $purchaseRequisition->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Budget</th>
                            <td>{{ $purchaseRequisition->budgetDepartment->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>Rp {{ number_format($purchaseRequisition->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($purchaseRequisition->status == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($purchaseRequisition->status == 1)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $purchaseRequisition->notes ?? '-' }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Items</h5>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Item</th>
                                <th>Deskripsi</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseRequisition->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
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
    function printPage() {
        var printContent = document.getElementById('printSection').innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }

    // Jika ada session 'print', jalankan fungsi print otomatis
    @if(session('print'))
        window.onload = function() {
            printPage();
        };
    @endif
</script>
@endsection
