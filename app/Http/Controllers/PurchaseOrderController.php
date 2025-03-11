<?php

namespace App\Http\Controllers;

use App\Models\BudgetDepartment;
use App\Models\Department;
use App\Models\PoApproval;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequisition;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua Purchase Order beserta relasi
        $query = PurchaseOrder::with('department', 'budgetDepartment', 'supplier', 'items');

        // Ambil data secara descending berdasarkan ID
        $purchaseOrders = $query->orderBy('id', 'desc')->get();

        return view('purchase-orders.index', compact('purchaseOrders'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user(); // Ambil user yang sedang login
        $departmentId = $user->department_id; // Ambil department dari user login

        $departments = Department::where('id', $departmentId)->get(); // Hanya department user
        $budgetDepartments = BudgetDepartment::where('department_id', $departmentId)
            ->where('status', 0)
            ->get(); // Budget department sesuai dan aktif
        $suppliers = Supplier::all();
        $units = Unit::all();

        return view('purchase-orders.create', compact('departments', 'budgetDepartments', 'suppliers', 'units', 'departmentId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'budget_department_id' => 'required',
            'supplier_id' => 'required',
            'po_date' => 'required|date',
            'notes' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.unit_price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor PO otomatis
            $poNumber = 'PO-' . strtoupper(Str::random(6));

            $purchaseOrder = PurchaseOrder::create([
                'user_id' => Auth::id(),
                'department_id' => $request->department_id,
                'budget_department_id' => $request->budget_department_id,
                'supplier_id' => $request->supplier_id,
                'po_number' => $poNumber,
                'po_date' => $request->po_date,
                'total_amount' => 0, // Akan dihitung ulang
                'status' => 0, // Default Pending
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalPrice = $item['quantity'] * $item['unit_price'];
                $totalAmount += $totalPrice;

                PurchaseOrderItem::create([
                    'po_id' => $purchaseOrder->id,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                ]);
            }

            // Update total amount di PO
            $purchaseOrder->update(['total_amount' => $totalAmount]);

            // Update Budget Department
            $budgetDepartment = BudgetDepartment::findOrFail($request->budget_department_id);

            if ($budgetDepartment->remaining_amount < $totalAmount) {
                DB::rollBack();
                return back()->with('error', 'Dana tidak mencukupi dalam Budget Department.');
            }

            $budgetDepartment->update([
                'used_amount' => $budgetDepartment->used_amount + $totalAmount,
                'remaining_amount' => $budgetDepartment->remaining_amount - $totalAmount,
            ]);

            // Tambahkan Approval Manager
            $manager = User::where('role', 'Manager')
                ->where('department_id', $request->department_id)
                ->first();

            if ($manager) {
                PoApproval::create([
                    'po_id' => $purchaseOrder->id,
                    'user_id' => $manager->id,
                    'level' => 1, // Manager
                    'status' => 0, // Pending
                ]);
            }

            // Tambahkan Approval Direktur
            $direktur = User::where('role', 'Direktur')->first();
            if ($direktur) {
                PoApproval::create([
                    'po_id' => $purchaseOrder->id,
                    'user_id' => $direktur->id,
                    'level' => 2, // Direktur
                    'status' => 0, // Pending
                ]);
            }

            DB::commit();

            // Redirect ke halaman show dengan print otomatis
            return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase Order berhasil dibuat dan menunggu approval.')
                ->with('print', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items', 'department', 'budgetDepartment', 'supplier', 'approvals.user'])
            ->findOrFail($id);

        $user = Auth::user();

        // Cek apakah user bisa approve
        $canApprove = PoApproval::where('po_id', $id)
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->exists();

        return view('purchase-orders.show', compact('purchaseOrder', 'canApprove'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);
        $departments = Department::all();
        $budgetDepartments = BudgetDepartment::where('status', 0)->get();
        $suppliers = Supplier::all();
        $units = Unit::all();

        return view('purchase-orders.edit', compact('purchaseOrder', 'departments', 'budgetDepartments', 'suppliers', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'budget_department_id' => 'required',
            'supplier_id' => 'required',
            'po_date' => 'required|date',
            'notes' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.unit_price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $oldBudgetDepartment = BudgetDepartment::findOrFail($purchaseOrder->budget_department_id);

            // Kembalikan anggaran sebelumnya
            $oldBudgetDepartment->update([
                'used_amount' => $oldBudgetDepartment->used_amount - $purchaseOrder->total_amount,
                'remaining_amount' => $oldBudgetDepartment->remaining_amount + $purchaseOrder->total_amount,
            ]);

            // Hitung total amount baru
            $totalAmount = 0;
            $existingItems = PurchaseOrderItem::where('po_id', $id)->get()->keyBy('id');

            foreach ($request->items as $itemData) {
                $totalPrice = $itemData['quantity'] * $itemData['unit_price'];
                $totalAmount += $totalPrice;

                if (!empty($itemData['id']) && isset($existingItems[$itemData['id']])) {
                    $existingItems[$itemData['id']]->update([
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'unit_id' => $itemData['unit_id'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $totalPrice,
                    ]);
                    unset($existingItems[$itemData['id']]);
                } else {
                    PurchaseOrderItem::create([
                        'po_id' => $purchaseOrder->id,
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'unit_id' => $itemData['unit_id'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $totalPrice,
                    ]);
                }
            }

            // Hapus item yang tidak ada di request
            if ($existingItems->isNotEmpty()) {
                PurchaseOrderItem::whereIn('id', $existingItems->keys())->delete();
            }

            // Validasi dana cukup sebelum update budget
            $newBudgetDepartment = BudgetDepartment::findOrFail($request->budget_department_id);
            if ($newBudgetDepartment->remaining_amount < $totalAmount) {
                DB::rollBack();
                return back()->with('error', 'Dana tidak mencukupi dalam Budget Department.');
            }

            // Update PO
            $purchaseOrder->update([
                'department_id' => $request->department_id,
                'budget_department_id' => $request->budget_department_id,
                'supplier_id' => $request->supplier_id,
                'po_date' => $request->po_date,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
            ]);

            // Kurangi anggaran baru
            $newBudgetDepartment->update([
                'used_amount' => $newBudgetDepartment->used_amount + $totalAmount,
                'remaining_amount' => $newBudgetDepartment->remaining_amount - $totalAmount,
            ]);

            DB::commit();

            return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase Order berhasil diperbarui dan siap dicetak.')
                ->with('print', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Hapus items dulu
            PurchaseOrderItem::where('po_id', $id)->delete();

            // Hapus PR utama
            PurchaseOrder::destroy($id);

            DB::commit();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $purchaseOrder = PurchaseOrder::with(['department', 'budgetDepartment', 'supplier', 'user', 'items', 'approvals.user'])
            ->findOrFail($id);

        return view('purchase-orders.print', compact('purchaseOrder'));
    }
}
