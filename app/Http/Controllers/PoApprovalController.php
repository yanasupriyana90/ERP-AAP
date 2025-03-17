<?php

namespace App\Http\Controllers;

use App\Models\BudgetDepartment;
use App\Models\PoApproval;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoApprovalController extends Controller
{
    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $po = PurchaseOrder::findOrFail($id);
        $user = Auth::user();

        // Cek apakah user adalah approver untuk PO ini
        $poApproval = PoApproval::where('po_id', $id)
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->firstOrFail();

        // Cek apakah user adalah direktur, dan apakah manager sudah approve
        if ($user->role == 'Direktur') {
            $managerApproval = PoApproval::where('po_id', $id)
                ->whereHas('user', function ($query) use ($po) {
                    $query->where('role', 'Manager')
                        ->where('department_id', $po->department_id);
                })
                ->where('status', 0)
                ->exists();

            if ($managerApproval) {
                return back()->with('error', 'Manager harus approve terlebih dahulu.');
            }
        }

        // Update status approval
        $poApproval->update([
            'status' => 1, // Approved
            'notes' => $request->notes
        ]);

        // Cek apakah semua approval sudah selesai
        $pendingApprovals = PoApproval::where('po_id', $id)->where('status', 0)->count();
        if ($pendingApprovals === 0) {
            // Cek apakah budget mencukupi sebelum approve
            $budget = BudgetDepartment::find($po->budget_department_id);
            if ($budget->remaining_amount < $po->total_amount) {
                return back()->with('error', 'Budget tidak mencukupi untuk Purchase Order ini.');
            }

            // Update status PO ke Approved
            $po->update(['status' => 1]);

            // Potong budget
            $budget->update([
                'used_amount' => $budget->used_amount + $po->total_amount,
                'remaining_amount' => $budget->remaining_amount - $po->total_amount
            ]);
        }

        return back()->with('success', 'Purchase Order berhasil diapprove.');
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $po = PurchaseOrder::findOrFail($id);
        $user = Auth::user();

        $poApproval = PoApproval::where('po_id', $id)
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->firstOrFail();

        // Jika ada yang reject, langsung update status PO ke rejected
        $poApproval->update([
            'status' => 2, // Rejected
            'notes' => $request->notes
        ]);

        // Cek apakah PO sudah disetujui sebelumnya, jika iya, kembalikan budget
        if ($po->status == 1) {
            $budget = BudgetDepartment::find($po->budget_department_id);
            $budget->update([
                'used_amount' => $budget->used_amount - $po->total_amount,
                'remaining_amount' => $budget->remaining_amount + $po->total_amount
            ]);
        }

        // Set PO menjadi rejected
        $po->update(['status' => 2]);

        return back()->with('error', 'Purchase Order ditolak.');
    }
}
