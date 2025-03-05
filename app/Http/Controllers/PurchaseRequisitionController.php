<?php

namespace App\Http\Controllers;

use App\Models\BudgetDepartment;
use App\Models\Department;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseRequisitions = PurchaseRequisition::with('department', 'budgetDepartment', 'items')->get();
        return view('purchase-requisitions.index', compact('purchaseRequisitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $budgetDepartments = BudgetDepartment::where('status', 0)->get();
        $units = Unit::all();

        return view('purchase-requisitions.create', compact('departments', 'budgetDepartments', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'budget_department_id' => 'required',
            'pr_date' => 'required|date',
            'notes' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.unit_price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor PR otomatis
            $prNumber = 'PR-' . strtoupper(Str::random(6));

            $purchaseRequisition = PurchaseRequisition::create([
                'user_id' => Auth::id(),
                'department_id' => $request->department_id,
                'budget_department_id' => $request->budget_department_id,
                'pr_number' => $prNumber,
                'pr_date' => $request->pr_date,
                'total_amount' => 0, // Akan dihitung ulang
                'status' => 0, // Default Pending
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalPrice = $item['quantity'] * $item['unit_price'];
                $totalAmount += $totalPrice;

                PurchaseRequisitionItem::create([
                    'pr_id' => $purchaseRequisition->id,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                ]);
            }

            // Update total amount di PR
            $purchaseRequisition->update(['total_amount' => $totalAmount]);

            DB::commit();

            //     return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase Requisition berhasil dibuat.');
            // } catch (\Exception $e) {
            //     DB::rollBack();
            //     return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            // }

            // Redirect ke halaman show dengan print otomatis
            return redirect()->route('purchase-requisitions.show', $purchaseRequisition->id)
                ->with('success', 'Purchase Requisition berhasil dibuat dan siap dicetak.')
                ->with('print', true); // Tambahkan session untuk trigger print
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchaseRequisition = PurchaseRequisition::with('department', 'budgetDepartment', 'items.unit')->findOrFail($id);
        return view('purchase-requisitions.show', compact('purchaseRequisition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchaseRequisition = PurchaseRequisition::with('items')->findOrFail($id);
        $departments = Department::all();
        $budgetDepartments = BudgetDepartment::where('status', 0)->get();
        $units = Unit::all();

        return view('purchase-requisitions.edit', compact('purchaseRequisition', 'departments', 'budgetDepartments', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'department_id' => 'required',
    //         'budget_department_id' => 'required',
    //         'pr_date' => 'required|date',
    //         'notes' => 'nullable|string',
    //         'items.*.item_name' => 'required|string',
    //         'items.*.quantity' => 'required|numeric|min:1',
    //         'items.*.unit_id' => 'required|exists:units,id',
    //         'items.*.unit_price' => 'required|numeric|min:1',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $purchaseRequisition = PurchaseRequisition::findOrFail($id);
    //         $purchaseRequisition->update([
    //             'department_id' => $request->department_id,
    //             'budget_department_id' => $request->budget_department_id,
    //             'pr_date' => $request->pr_date,
    //             'notes' => $request->notes,
    //         ]);

    //         // Hapus item lama dan tambahkan yang baru
    //         PurchaseRequisitionItem::where('pr_id', $id)->delete();

    //         $totalAmount = 0;
    //         foreach ($request->items as $item) {
    //             $totalPrice = $item['quantity'] * $item['unit_price'];
    //             $totalAmount += $totalPrice;

    //             PurchaseRequisitionItem::create([
    //                 'pr_id' => $purchaseRequisition->id,
    //                 'item_name' => $item['item_name'],
    //                 'description' => $item['description'] ?? null,
    //                 'quantity' => $item['quantity'],
    //                 'unit_id' => $item['unit_id'],
    //                 'unit_price' => $item['unit_price'],
    //                 'total_price' => $totalPrice,
    //             ]);
    //         }

    //         // Redirect ke halaman show dengan print otomatis
    //         return redirect()->route('purchase-requisitions.show', $purchaseRequisition->id)
    //             ->with('success', 'Purchase Requisition berhasil diperbarui dan siap dicetak.')
    //             ->with('print', true); // Tambahkan session untuk trigger print
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'budget_department_id' => 'required',
            'pr_date' => 'required|date',
            'notes' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.unit_price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $purchaseRequisition = PurchaseRequisition::findOrFail($id);
            $purchaseRequisition->update([
                'department_id' => $request->department_id,
                'budget_department_id' => $request->budget_department_id,
                'pr_date' => $request->pr_date,
                'notes' => $request->notes,
            ]);

            $existingItems = PurchaseRequisitionItem::where('pr_id', $id)->get()->keyBy('id');
            $totalAmount = 0;

            foreach ($request->items as $itemData) {
                $totalPrice = $itemData['quantity'] * $itemData['unit_price'];
                $totalAmount += $totalPrice;

                // Jika item sudah ada, update
                if (!empty($itemData['id']) && isset($existingItems[$itemData['id']])) {
                    $existingItems[$itemData['id']]->update([
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'unit_id' => $itemData['unit_id'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $totalPrice,
                    ]);
                    unset($existingItems[$itemData['id']]); // Hapus dari daftar yang ada
                } else {
                    // Jika item baru, tambahkan
                    PurchaseRequisitionItem::create([
                        'pr_id' => $purchaseRequisition->id,
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'unit_id' => $itemData['unit_id'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $totalPrice,
                    ]);
                }
            }

            // Hapus item yang tidak ada di request (berarti dihapus oleh user)
            if ($existingItems->isNotEmpty()) {
                PurchaseRequisitionItem::whereIn('id', $existingItems->keys())->delete();
            }

            // Update total amount di PR
            $purchaseRequisition->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('purchase-requisitions.show', $purchaseRequisition->id)
                ->with('success', 'Purchase Requisition berhasil diperbarui dan siap dicetak.')
                ->with('print', true); // Tambahkan session untuk trigger print
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
            PurchaseRequisitionItem::where('pr_id', $id)->delete();

            // Hapus PR utama
            PurchaseRequisition::destroy($id);

            DB::commit();
            return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase Requisition berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
