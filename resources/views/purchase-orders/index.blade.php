@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">List Purchase Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="d-flex justify-content-between mb-2 mt-4">
                <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah
                    Purchase Order</a>
            </div>
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
                                                <a href="{{ route('purchase-orders.show', $po->id) }}"
                                                    class="btn btn-info btn-sm mr-1" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Tombol Print (Hanya Jika Approved, dan hanya pembuatnya yang bisa print) -->
                                                @if ($po->status == 1 && ($po->user_id == Auth::id() || Auth::user()->role === 'Superuser'))
                                                    <a href="{{ route('purchase-orders.print', $po->id) }}"
                                                        class="btn btn-secondary btn-sm mr-1" title="Print"
                                                        target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                @endif

                                                <!-- Cek apakah user adalah pembuat PO atau Superuser -->
                                                @if (Auth::user()->role === 'Superuser' || $po->user_id == Auth::id())
                                                    @if ($po->status == 0)
                                                        <div class="btn-group">
                                                            <!-- Tombol Edit -->
                                                            <a href="{{ route('purchase-orders.edit', $po->id) }}"
                                                                class="btn btn-warning btn-sm mr-1" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <!-- Tombol Hapus -->
                                                            <form action="{{ route('purchase-orders.destroy', $po->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
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
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
