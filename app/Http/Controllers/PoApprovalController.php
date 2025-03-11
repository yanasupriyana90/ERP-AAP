<?php

namespace App\Http\Controllers;

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
            $po->update(['status' => 1]); // Approved
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

        $po->update(['status' => 2]); // Set PO menjadi rejected

        return back()->with('error', 'Purchase Order ditolak.');
    }
}
