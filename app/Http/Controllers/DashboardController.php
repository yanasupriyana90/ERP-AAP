<?php

namespace App\Http\Controllers;

use App\Models\BudgetDepartment;
use App\Models\Department;
use App\Models\PoApproval;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $totalUsers = User::count();
    //     $totalDepartments = Department::count();
    //     $totalBudgets = BudgetDepartment::where('status', 0)->count();
    //     $totalSuppliers = Supplier::count();
    //     $totalPO = PurchaseOrder::count();

    //     // Data untuk Chart Purchase Orders per bulan
    //     $poData = PurchaseOrder::selectRaw('MONTH(po_date) as month, COUNT(*) as count')
    //         ->groupBy('month')
    //         ->pluck('count', 'month')->toArray();

    //     $poMonths = array_keys($poData);
    //     $poCounts = array_values($poData);

    //     // Data untuk Budget Chart
    //     $usedBudget = BudgetDepartment::sum('used_amount');
    //     $remainingBudget = BudgetDepartment::sum('amount') - $usedBudget;

    //     return view('dashboard.index', compact(
    //         'totalUsers',
    //         'totalDepartments',
    //         'totalBudgets',
    //         'totalSuppliers',
    //         'totalPO',
    //         'poMonths',
    //         'poCounts',
    //         'usedBudget',
    //         'remainingBudget'
    //     ));
    // }

    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $totalUsers = 0;
        $totalDepartments = 0;
        $totalBudgets = 0;
        $totalSuppliers = Supplier::count();
        $totalPO = 0;
        $pendingPO = 0;
        $approvedPO = 0;
        $rejectedPO = 0;
        $recentPOs = collect();

        if ($role === 'Staff') {
            $totalBudgets = BudgetDepartment::where('department_id', $user->department_id)->count();
            $totalPO = PurchaseOrder::where('user_id', $user->id)->count();
            $pendingPO = PurchaseOrder::where('user_id', $user->id)->where('status', 0)->count();
            $approvedPO = PurchaseOrder::where('user_id', $user->id)->where('status', 1)->count();
            $rejectedPO = PurchaseOrder::where('user_id', $user->id)->where('status', 2)->count();
            $recentPOs = PurchaseOrder::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(5)->get();
        } elseif ($role === 'Supervisor') {
            $totalBudgets = BudgetDepartment::where('department_id', $user->department_id)->count();
            $totalPO = PurchaseOrder::where('department_id', $user->department_id)->count();
            $pendingPO = PurchaseOrder::where('department_id', $user->department_id)->where('status', 0)->count();
            $approvedPO = PurchaseOrder::where('department_id', $user->department_id)->where('status', 1)->count();
            $rejectedPO = PurchaseOrder::where('department_id', $user->department_id)->where('status', 2)->count();
            $recentPOs = PurchaseOrder::where('department_id', $user->department_id)->orderBy('created_at', 'desc')->limit(5)->get();
        } elseif ($role === 'Manager') {
            $totalUsers = User::where('department_id', $user->department_id)->count();
            $totalBudgets = BudgetDepartment::where('department_id', $user->department_id)->count();
            $totalPO = PurchaseOrder::where('department_id', $user->department_id)->count();
            $pendingPO = PurchaseOrder::where('department_id', $user->department_id)->where('status', 0)->count();
            $approvedPO = PurchaseOrder::where('department_id', $user->department_id)->where('status', 1)->count();
            $rejectedPO = PurchaseOrder::where('department_id', $user->department_id)->where('status', 2)->count();
            // $approvedPO = PoApproval::where('department_id', $user->department_id)
            //     ->where('status', 1) // Status Approved
            //     ->count();

            // $rejectedPO = PoApproval::where('department_id', $user->department_id)
            //     ->where('status', 2) // Status Rejected
            //     ->count();
        } elseif ($role === 'Superuser' || $role === 'Direktur') {
            $totalUsers = User::count();
            $totalDepartments = Department::count();
            $totalBudgets = BudgetDepartment::count();
            $totalPO = PurchaseOrder::count();
            $pendingPO = PurchaseOrder::where('status', 0)->count();
            $approvedPO = PurchaseOrder::where('status', 1)->count();
            $rejectedPO = PurchaseOrder::where('status', 2)->count();
            // $approvedPO = PoApproval::where('user_id', $user->id)
            //     ->where('status', 1) // Status Approved
            //     ->count();

            // $rejectedPO = PoApproval::where('user_id', $user->id)
            //     ->where('status', 2) // Status Rejected
            //     ->count();
        }

        $poData = PurchaseOrder::selectRaw('MONTH(po_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $poMonths = array_keys($poData);
        $poCounts = array_values($poData);

        $usedBudget = BudgetDepartment::sum('used_amount');
        $remainingBudget = BudgetDepartment::sum('amount') - $usedBudget;

        return view('dashboard.index', compact(
            'totalUsers',
            'totalDepartments',
            'totalBudgets',
            'totalSuppliers',
            'totalPO',
            'pendingPO',
            'approvedPO',
            'rejectedPO',
            'recentPOs',
            'poMonths',
            'poCounts',
            'usedBudget',
            'remainingBudget',
            'role'
        ));
    }
}
