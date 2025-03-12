@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Purchase Orders</h4>
                <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Purchase Order
                </a>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($purchaseOrders->isEmpty())
                    <div class="alert alert-warning text-center">Tidak ada Purchase Order yang tersedia.</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <!-- Wrapper agar tabel bisa di-scroll horizontal -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="poTable">
                                <thead class="table-dark">
                                    <tr class="text-nowrap text-center">
                                        <th>#</th>
                                        <th>PO Number</th>
                                        <th>Tanggal PO</th>
                                        <th>Department</th>
                                        <th>Budget</th>
                                        <th>Supplier</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrders as $po)
                                        <tr class="text-nowrap">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $po->po_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') }}</td>
                                            <td>{{ $po->department->name ?? '-' }}</td>
                                            <td>{{ $po->budgetDepartment->name ?? '-' }}</td>
                                            <td>{{ $po->supplier->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if ($po->status == 0)
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($po->status == 1)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <!-- Tombol Lihat -->
                                                    <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-info btn-sm mr-1">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>

                                                    <!-- Tombol Print (Hanya Jika Approved, dan hanya pembuatnya yang bisa print) -->
                                                    @if ($po->status == 1 && ($po->user_id == Auth::id() || Auth::user()->role === 'Superuser'))
                                                        <a href="{{ route('purchase-orders.print', $po->id) }}" class="btn btn-secondary btn-sm mr-1" target="_blank">
                                                            <i class="fas fa-print"></i> Print
                                                        </a>
                                                    @endif

                                                    <!-- Cek apakah user adalah pembuat PO atau Superuser -->
                                                    @if (Auth::user()->role === 'Superuser' || $po->user_id == Auth::id())
                                                        @if ($po->status == 0)
                                                            <!-- Dropdown untuk Edit & Hapus -->
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-warning btn-sm dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-cog"></i> Aksi
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item text-warning" href="{{ route('purchase-orders.edit', $po->id) }}">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </a>
                                                                    <form action="{{ route('purchase-orders.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="fas fa-trash"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>



                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- End table-responsive -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#poTable').DataTable({
                "scrollX": true // Menambahkan fitur scroll horizontal pada DataTable
            });
        });
    </script>
@endsection
