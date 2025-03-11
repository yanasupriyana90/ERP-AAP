@extends('layouts.app')

@section('content')
    <div class="container">
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

        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Department</label>
                <select name="department_id" class="form-control" readonly>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Budget Department</label>
                <select name="budget_department_id" class="form-control">
                    @foreach ($budgetDepartments as $budgetDepartment)
                        <option value="{{ $budgetDepartment->id }}">{{ $budgetDepartment->name }} (Amount = Rp
                            {{ number_format($budgetDepartment->amount, 0, ',', '.') }}) (Remaining = Rp
                            {{ number_format($budgetDepartment->remaining_amount, 0, ',', '.') }}) </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="po_date" class="form-label">PO Date</label>
                <input type="date" name="po_date" id="po_date" class="form-control">
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control"></textarea>
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
                        <td><input type="number" name="items[0][quantity]" class="form-control quantity" data-index="0"
                                oninput="calculateTotal(0)"></td>
                        <td>
                            <select name="items[0][unit_id]" class="form-control">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[0][unit_price]" class="form-control unit_price" data-index="0"
                                oninput="calculateTotal(0)"></td>
                        <td><input type="text" name="items[0][total_price]" class="form-control total_price" readonly>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-primary" onclick="addItem()">Add Item</button>

            <div class="mt-3">
                <label for="total_amount" class="form-label">Total Amount</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-success mt-3">Create</button>
        </form>
    </div>

    <script>
        function calculateTotal(index) {
            let quantity = document.querySelector(`[name="items[${index}][quantity]"]`).value;
            let unitPrice = document.querySelector(`[name="items[${index}][unit_price]"]`).value;
            let totalPrice = quantity * unitPrice;
            document.querySelector(`[name="items[${index}][total_price]"]`).value = totalPrice.toFixed(2);
            updateTotalAmount();
        }

        function updateTotalAmount() {
            let totalAmount = 0;
            document.querySelectorAll(".total_price").forEach(item => {
                totalAmount += parseFloat(item.value) || 0;
            });
            document.getElementById("total_amount").value = totalAmount.toFixed(2);
        }

        function addItem() {
            let table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
            let index = table.rows.length;
            let newRow = table.insertRow();

            newRow.innerHTML = `
            <td><input type="text" name="items[${index}][item_name]" class="form-control"></td>
            <td><input type="text" name="items[${index}][description]" class="form-control"></td>
            <td><input type="number" name="items[${index}][quantity]" class="form-control quantity" data-index="${index}" oninput="calculateTotal(${index})"></td>
            <td>
                <select name="items[${index}][unit_id]" class="form-control">
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][unit_price]" class="form-control unit_price" data-index="${index}" oninput="calculateTotal(${index})"></td>
            <td><input type="text" name="items[${index}][total_price]" class="form-control total_price" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
        `;
        }

        function removeRow(button) {
            let row = button.closest("tr");
            row.remove();
            updateTotalAmount();
        }
    </script>
@endsection
