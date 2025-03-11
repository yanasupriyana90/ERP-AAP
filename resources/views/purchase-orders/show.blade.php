{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Detail Purchase Order</h4>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button onclick="printPage()" class="btn btn-success mb-3">
                    <i class="fas fa-print"></i> Print
                </button>

                <div class="card" id="printSection">
                    <div class="card-body">
                        <h5>Purchase Order</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>PR Number</th>
                                <td>{{ $purchaseOrder->po_number }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal PR</th>
                                <td>{{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $purchaseOrder->department->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Budget</th>
                                <td>{{ $purchaseOrder->budgetDepartment->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $purchaseOrder->supplier->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($purchaseOrder->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($purchaseOrder->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $purchaseOrder->notes ?? '-' }}</td>
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
                                @foreach ($purchaseOrder->items as $index => $item)
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
        @if (session('print'))
            window.onload = function() {
                printPage();
            };
        @endif
    </script>
@endsection --}}


{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Detail Purchase Order</h4>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>PO Number</th>
                                <td>{{ $purchaseOrder->po_number }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal PO</th>
                                <td>{{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $purchaseOrder->department->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($purchaseOrder->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($purchaseOrder->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Approval Status</h5>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Approver</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrder->approvals as $approval)
                                    <tr>
                                        <td>{{ $approval->user->name }}</td>
                                        <td>{{ $approval->level == 1 ? 'Manager' : 'Direktur' }}</td>
                                        <td>
                                            @if ($approval->status == 0)
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($approval->status == 1)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $approval->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php
                            $userApproval = $purchaseOrder->approvals
                                ->where('user_id', auth()->id())
                                ->where('status', 0)
                                ->first();
                        @endphp

                        @if ($userApproval)
                            <button class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                                <i class="fas fa-check"></i> Approve
                            </button>

                            <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Purchase Order</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('purchase-orders.approve', $purchaseOrder->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="approveNotes" class="form-label">Alasan Approve</label>
                            <textarea class="form-control" name="notes" id="approveNotes" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Purchase Order</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('purchase-orders.reject', $purchaseOrder->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejectNotes" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" name="notes" id="rejectNotes" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak PO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection --}}


@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Detail Purchase Order</h4>

        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Informasi Purchase Order</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
                        <p><strong>Tanggal PO:</strong>
                            {{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d-m-Y') }}</p>
                        <p><strong>Department:</strong> {{ $purchaseOrder->department->name ?? '-' }}</p>
                        <p><strong>Budget:</strong> {{ $purchaseOrder->budgetDepartment->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? '-' }}</p>
                        <p><strong>Total Amount:</strong> <span class="text-success font-weight-bold">Rp
                                {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</span></p>
                        <p><strong>Status:</strong>
                            @if ($purchaseOrder->status == 0)
                                <span class="badge badge-warning">Pending</span>
                            @elseif ($purchaseOrder->status == 1)
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </p>
                        <p><strong>Notes:</strong> {{ $purchaseOrder->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Items -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Purchase Order Items</h5>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Approval -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Approval History</h5>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Approver</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->approvals as $index => $approval)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $approval->user->name }}</td>
                                <td>{{ $approval->user->role }}</td>
                                <td>
                                    @if ($approval->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($approval->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $approval->notes ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($approval->updated_at)->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Approve/Reject Buttons -->
        @if ($canApprove)
        <div class="card mt-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-tasks"></i> Approval Actions</h5>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Silakan pilih tindakan yang ingin dilakukan terhadap Purchase Order ini.</p>

                <!-- Tombol Approve -->
                <button type="button" class="btn btn-lg btn-success mx-2 shadow-sm" data-toggle="modal" data-target="#approveModal">
                    <i class="fas fa-check-circle"></i> Approve
                </button>

                <!-- Tombol Reject -->
                <button type="button" class="btn btn-lg btn-danger mx-2 shadow-sm" data-toggle="modal" data-target="#rejectModal">
                    <i class="fas fa-times-circle"></i> Reject
                </button>
            </div>
        </div>

            <!-- Modal Approve -->
            <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approveModalLabel">Alasan Approve</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('purchase-orders.approve', $purchaseOrder->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="approveNotes">Masukkan alasan approve</label>
                                    <textarea name="notes" id="approveNotes" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Reject -->
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejectModalLabel">Alasan Reject</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('purchase-orders.reject', $purchaseOrder->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="rejectNotes">Masukkan alasan reject</label>
                                    <textarea name="notes" id="rejectNotes" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
