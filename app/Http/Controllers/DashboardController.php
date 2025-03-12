<?php

namespace App\Http\Controllers;

use App\Models\BudgetDepartment;
use App\Models\Department;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalDepartments = Department::count();
        $totalBudgets = BudgetDepartment::where('status', 0)->count();
        $totalSuppliers = Supplier::count();
        $totalPO = PurchaseOrder::count();

        // Data untuk Chart Purchase Orders per bulan
        $poData = PurchaseOrder::selectRaw('MONTH(po_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $poMonths = array_keys($poData);
        $poCounts = array_values($poData);

        // Data untuk Budget Chart
        $usedBudget = BudgetDepartment::sum('used_amount');
        $remainingBudget = BudgetDepartment::sum('amount') - $usedBudget;

        return view('dashboard', compact(
            'totalUsers',
            'totalDepartments',
            'totalBudgets',
            'totalSuppliers',
            'totalPO',
            'poMonths',
            'poCounts',
            'usedBudget',
            'remainingBudget'
        ));
    }
}
