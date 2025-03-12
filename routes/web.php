<?php

use App\Http\Controllers\BudgetDepartmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PoApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::resource('budget-departments', BudgetDepartmentController::class);
    Route::resource('units', UnitController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase-requisitions', PurchaseRequisitionController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::post('/purchase-orders/{id}/approve', [PoApprovalController::class, 'approve'])->name('purchase-orders.approve');
    Route::post('/purchase-orders/{id}/reject', [PoApprovalController::class, 'reject'])->name('purchase-orders.reject');
    Route::get('/purchase-orders/{id}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
});



require __DIR__ . '/auth.php';
