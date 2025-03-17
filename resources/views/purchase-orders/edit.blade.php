@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Edit Purchase Order</h2>

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
            <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-3 mb-3">
                        <label>Department</label>
                        <select name="department_id" class="form-control" readonly>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $purchaseOrder->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label>Budget Department</label>
                        <select name="budget_department_id" class="form-control">
                            @foreach ($budgetDepartments as $budgetDepartment)
                                <option value="{{ $budgetDepartment->id }}"
                                    {{ old('budget_department_id', $purchaseOrder->budget_department_id) == $budgetDepartment->id ? 'selected' : '' }}>
                                    {{ $budgetDepartment->name }}
                                    (Amount = Rp {{ number_format($budgetDepartment->amount, 0, ',', '.') }})
                                    (Remaining = Rp {{ number_format($budgetDepartment->remaining_amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label for="po_date">PO Date</label>
                        <input type="date" name="po_date" id="po_date" class="form-control"
                            value="{{ old('po_date', $purchaseOrder->po_date) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes', $purchaseOrder->notes) }}</textarea>
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
                        @foreach ($purchaseOrder->items as $index => $item)
                            <tr>
                                <td><input type="text" name="items[{{ $index }}][item_name]" class="form-control"
                                        value="{{ old('items.' . $index . '.item_name', $item->item_name) }}"></td>
                                <td><input type="text" name="items[{{ $index }}][description]"
                                        class="form-control"
                                        value="{{ old('items.' . $index . '.description', $item->description) }}"></td>
                                <td><input type="number" name="items[{{ $index }}][quantity]"
                                        class="form-control quantity" data-index="{{ $index }}"
                                        oninput="calculateTotal({{ $index }})"
                                        value="{{ old('items.' . $index . '.quantity', $item->quantity) }}"></td>
                                <td>
                                    <select name="items[{{ $index }}][unit_id]" class="form-control">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('items.' . $index . '.unit_id', $item->unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="items[{{ $index }}][unit_price]"
                                        class="form-control unit_price text-right" data-index="{{ $index }}"
                                        oninput="formatCurrency(this); calculateTotal({{ $index }})"
                                        value="Rp {{ number_format((float) old('items.' . $index . '.unit_price', $item->unit_price), 0, ',', '.') }}">
                                </td>

                                <td>
                                    <input type="text" name="items[{{ $index }}][total_price]"
                                        class="form-control total_price text-right" readonly
                                        value="Rp {{ number_format((float) old('items.' . $index . '.total_price', $item->total_price), 0, ',', '.') }}">
                                </td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i
                                            class="fas fa-trash"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary" onclick="addItem()">Add Item</button>

                <div class="mt-3">
                    <div class="card bg-light shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Total Amount</h5>
                            <h3 id="total_amount" class="font-weight-bold text-primary">Rp
                                {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = {{ count($purchaseOrder->items) }};

        function addItem() {
            let table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];

            let newRow = table.insertRow();
            newRow.innerHTML = `
                <td><input type="text" name="items[${itemIndex}][item_name]" class="form-control"></td>
                <td><input type="text" name="items[${itemIndex}][description]" class="form-control"></td>
                <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" data-index="${itemIndex}" oninput="calculateTotal(${itemIndex})"></td>
                <td>
                    <select name="items[${itemIndex}][unit_id]" class="form-control">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="items[${itemIndex}][unit_price]" class="form-control unit_price text-right" data-index="${itemIndex}" oninput="formatCurrency(this); calculateTotal(${itemIndex})">
                </td>
                <td>
                    <input type="text" name="items[${itemIndex}][total_price]" class="form-control total_price text-right" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            itemIndex++;
        }

        function removeRow(button) {
            let row = button.closest("tr");
            row.remove();
            updateTotalAmount();
        }

        function calculateTotal(index) {
            let quantity = document.querySelector(`[name="items[${index}][quantity]"]`).value;
            let unitPriceInput = document.querySelector(`[name="items[${index}][unit_price]"]`);
            let totalPriceInput = document.querySelector(`[name="items[${index}][total_price]"]`);

            let unitPrice = unitPriceInput.value.replace(/\D/g, ""); // Hanya angka
            let totalPrice = (quantity * unitPrice) || 0;

            // Update Total Price dan pastikan tetap dalam format Rupiah
            totalPriceInput.value = formatRupiah(totalPrice.toString());

            // Format ulang Unit Price setiap kali ada perubahan
            formatCurrency(unitPriceInput);

            updateTotalAmount();
        }

        function updateTotalAmount() {
            let totalAmount = 0;
            document.querySelectorAll(".total_price").forEach(item => {
                let value = item.value.replace(/\D/g, "");
                totalAmount += parseFloat(value) || 0;
            });

            document.getElementById("total_amount").innerText = formatRupiah(totalAmount.toString());
        }

        function formatRupiah(value) {
            let numberString = value.replace(/\D/g, ""); // Hanya angka
            return "Rp " + new Intl.NumberFormat('id-ID').format(numberString);
        }

        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, "");
            if (!value) {
                input.value = "";
                return;
            }
            input.value = formatRupiah(value);
        }

        // Format semua harga ketika halaman pertama kali dimuat
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".unit_price").forEach(input => {
                let value = input.value.replace(/\D/g, "");
                input.value = value ? formatRupiah(value) : "";
            });

            document.querySelectorAll(".total_price").forEach(input => {
                let value = input.value.replace(/\D/g, "");
                input.value = value ? formatRupiah(value) : "";
            });

            updateTotalAmount();
        });

        // Hapus format Rupiah sebelum form dikirim ke backend
        document.querySelector("form").addEventListener("submit", function() {
            document.querySelectorAll(".unit_price, .total_price").forEach(input => {
                input.value = input.value.replace(/\D/g, ""); // Hapus format sebelum dikirim ke backend
            });
        });
    </script>


@endsection
