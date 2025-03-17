@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Create Purchase Order</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Tampilkan error jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('purchase-orders.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-3 mb-3">
                        <label>Department</label>
                        <select name="department_id" class="form-control" readonly>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label>Budget Department</label>
                        <select name="budget_department_id" class="form-control">
                            @foreach ($budgetDepartments as $budgetDepartment)
                                <option value="{{ $budgetDepartment->id }}"
                                    {{ old('budget_department_id') == $budgetDepartment->id ? 'selected' : '' }}>
                                    {{ $budgetDepartment->name }}
                                    (Amount = Rp {{ number_format($budgetDepartment->amount, 0, ',', '.') }})
                                    (Remaining = Rp {{ number_format($budgetDepartment->remaining_amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label for="po_date" class="form-label">PO Date</label>
                        <input type="date" name="po_date" id="po_date" class="form-control"
                            value="{{ old('po_date', date('Y-m-d')) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <h4>Items</h4>
                <table class="table" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="items[0][item_name]" class="form-control"></td>
                            <td><input type="text" name="items[0][description]" class="form-control"></td>
                            <td><input type="number" step="0.01" name="items[0][quantity]" class="form-control quantity"
                                    data-index="0" oninput="calculateTotal(0)"></td>
                            <td>
                                <select name="items[0][unit_id]" class="form-control">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- Input Unit Price -->
                            <td>
                                <input type="text" name="items[0][unit_price]" class="form-control unit_price text-right"
                                    data-index="0" value="{{ old('items.0.unit_price') }}"
                                    oninput="formatCurrency(this); calculateTotal(0)">
                            </td>

                            <!-- Input Total Price (readonly) -->
                            <td>
                                <input type="text" name="items[0][total_price]"
                                    class="form-control total_price text-right" value="{{ old('items.0.total_price') }}"
                                    readonly>
                            </td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary" onclick="addItem()">Add Item</button>

                <div class="mt-3">
                    <div class="card bg-light shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Total Amount</h5>
                            <h3 id="total_amount" class="font-weight-bold text-primary">Rp 0</h3>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(value) {
            let numberString = value.replace(/\D/g, "");
            return new Intl.NumberFormat('id-ID').format(numberString);
        }

        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, "");
            if (!value) {
                input.value = "";
                return;
            }
            input.value = "Rp " + formatRupiah(value);
        }

        function calculateTotal(index) {
            let quantity = document.querySelector(`[name="items[${index}][quantity]"]`).value;
            let unitPrice = document.querySelector(`[name="items[${index}][unit_price]"]`).value.replace(/\D/g, "");
            let totalPrice = Math.round(quantity * unitPrice * 100) / 100; // Membulatkan ke 2 desimal

            document.querySelector(`[name="items[${index}][total_price]"]`).value = "Rp " + formatRupiah(totalPrice
                .toString());
            updateTotalAmount();
        }

        function updateTotalAmount() {
            let totalAmount = 0;
            document.querySelectorAll(".total_price").forEach(item => {
                let value = item.value.replace(/\D/g, "");
                totalAmount += parseFloat(value) || 0;
            });

            document.getElementById("total_amount").innerText = "Rp " + formatRupiah(totalAmount.toString());
        }

        document.querySelector("form").addEventListener("submit", function() {
            document.querySelectorAll(".unit_price, .total_price").forEach(input => {
                input.value = input.value.replace(/\D/g, ""); // Hapus format sebelum dikirim ke backend
            });
        });
    </script>

    <script>
        let itemIndex = 1; // Indeks awal untuk item

        function addItem() {
            let tableBody = document.querySelector("#itemsTable tbody");

            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                <td><input type="text" name="items[${itemIndex}][item_name]" class="form-control"></td>
                <td><input type="text" name="items[${itemIndex}][description]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[${itemIndex}][quantity]" class="form-control quantity"
                    data-index="${itemIndex}" oninput="calculateTotal(${itemIndex})"></td>
                <td>
                    <select name="items[${itemIndex}][unit_id]" class="form-control">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="items[${itemIndex}][unit_price]" class="form-control unit_price text-right"
                        data-index="${itemIndex}" oninput="formatCurrency(this); calculateTotal(${itemIndex})"></td>
                <td><input type="text" name="items[${itemIndex}][total_price]" class="form-control total_price text-right" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
            `;

            tableBody.appendChild(newRow);
            itemIndex++; // Naikkan indeks untuk item berikutnya
        }

        function removeRow(button) {
            button.closest("tr").remove();
            updateTotalAmount();
        }
    </script>

@endsection
