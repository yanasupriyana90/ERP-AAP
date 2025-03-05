<?php

namespace App\Http\Controllers;

use App\Models\BudgetDepartment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgetDepartments = BudgetDepartment::with('user', 'department')->get();
        return view('budget-departments.index', compact('budgetDepartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $departments = Department::all();
        return view('budget-departments.create', compact('users', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'name' => 'required',
            'amount' => 'required|numeric',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'status' => 'required|numeric|in:0,1',
        ]);

        // Ambil bulan dan tahun saat ini
        $yearMonth = date('Ym');

        // Cari kode terakhir dengan format BD-YYYYMM-XXXXX
        $latestBudget = BudgetDepartment::where('code', 'like', "BD-{$yearMonth}-%")
            ->orderBy('code', 'desc')
            ->first();

        // Ambil nomor urut terakhir, jika ada +1, jika tidak mulai dari 00001
        if ($latestBudget) {
            $lastNumber = (int) substr($latestBudget->code, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        // Generate kode baru
        $newCode = "BD-{$yearMonth}-{$newNumber}";

        $remainingAmount = $request->amount; // Sisa dana awal = jumlah dana

        BudgetDepartment::create([
            'user_id' =>Auth::id(),
            'department_id' => $request->department_id,
            'code' => $newCode,
            'name' => $request->name,
            'amount' => $request->amount,
            'used_amount' => 0,
            'remaining_amount' => $remainingAmount,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'status' => $request->status,
        ]);

        return redirect()->route('budget-departments.index')->with('success', 'Budget Department berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetDepartment $budgetDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetDepartment $budgetDepartment)
    {
        $users = User::all();
        $departments = Department::all();
        return view('budget-departments.edit', compact('budgetDepartment', 'users', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'name' => 'required',
            'amount' => 'required|numeric',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        $budgetDepartment = BudgetDepartment::findOrFail($id);

        $budgetDepartment->update([
            'department_id' => $request->department_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'status' => $request->status,
        ]);

        return redirect()->route('budget-departments.index')->with('success', 'Budget Department berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetDepartment $budgetDepartment)
    {
        $budgetDepartment->delete();
        return redirect()->route('budget-departments.index')->with('success', 'Budget berhasil dihapus!');
    }
}
