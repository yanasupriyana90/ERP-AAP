<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Purchase Order</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .signature {
            height: 80px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <section class="invoice">
            <!-- Header -->
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="page-header">
                        <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 50px;">
                        <span class="ml-2">Purchase Order</span>
                        <small class="float-right">Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</small>
                    </h2>
                    <hr>
                </div>
            </div>

            <!-- Informasi PO -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <address>
                        Department: <strong>{{ $purchaseOrder->department->name ?? '-' }}</strong><br>
                        Budget: <strong>{{ $purchaseOrder->budgetDepartment->name ?? '-' }}<br></strong>
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    Supplier:
                    <address>
                        <strong>{{ $purchaseOrder->supplier->name ?? '-' }}</strong><br>
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    PO Number: <strong>{{ $purchaseOrder->po_number }}<br></strong>
                    Tanggal PO:
                    <strong>{{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d-m-Y') }}<br></strong>
                    Total Amount: <strong>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}<br></strong>
                    Status:
                    @if ($purchaseOrder->status == 1)
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </div>
            </div>

            <!-- Tabel Item -->
            <div class="row mt-3">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
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
                                    <td class="text-start">{{ $item->item_name }}</td>
                                    <td class="text-start">{{ $item->description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tanda Tangan -->
            <div class="row mt-5 text-center">
                <div class="col-4">
                    <p><strong>Pembuat PO</strong></p>
                    <p>{{ $purchaseOrder->user->name }}</p>
                    <div class="signature"></div>
                    <p>______________________</p>
                </div>
                <div class="col-4">
                    <p><strong>Approval Manager</strong></p>
                    <p>{{ optional($purchaseOrder->approvals->where('level', 1)->first())->user->name ?? '-' }}</p>
                    <div class="signature"></div>
                    <p>______________________</p>
                    <p>{{ optional($purchaseOrder->approvals->where('level', 1)->first())->updated_at ? \Carbon\Carbon::parse(optional($purchaseOrder->approvals->where('level', 1)->first())->updated_at)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                <div class="col-4">
                    <p><strong>Approval Direktur</strong></p>
                    <p>{{ optional($purchaseOrder->approvals->where('level', 2)->first())->user->name ?? '-' }}</p>
                    <div class="signature"></div>
                    <p>______________________</p>
                    <p>{{ optional($purchaseOrder->approvals->where('level', 2)->first())->updated_at ? \Carbon\Carbon::parse(optional($purchaseOrder->approvals->where('level', 2)->first())->updated_at)->format('d-m-Y') : '-' }}
                    </p>
                </div>
            </div>

            <!-- Tombol Print -->
            <div class="text-center mt-4 no-print">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button class="btn btn-danger" onclick="window.close();">
                    <i class="fas fa-times"></i> Tutup
                </button>

            </div>

        </section>
    </div>

    <!-- Auto Print -->
    <script>
        window.addEventListener("load", function() {
            window.print();
        });
    </script>

</body>

</html>
