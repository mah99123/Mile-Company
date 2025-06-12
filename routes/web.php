<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Meym\CampaignController;
use App\Http\Controllers\PhoneTech\ProductController;
use App\Http\Controllers\PhoneTech\SaleController;
use App\Http\Controllers\PhoneTech\SupplierController;
use App\Http\Controllers\CarImport\CarImportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Meym Platform - Campaign Management
    Route::middleware(['permission:access meym'])->prefix('meym')->name('meym.')->group(function () {
        Route::resource('campaigns', CampaignController::class);
        Route::post('campaigns/{campaign}/updates', [CampaignController::class, 'addUpdate'])->name('campaigns.add-update');
        Route::post('campaigns/{campaign}/whatsapp', [CampaignController::class, 'addWhatsappMessage'])->name('campaigns.add-whatsapp');
        Route::get('campaigns/{campaign}/report', [CampaignController::class, 'report'])->name('campaigns.report');
    });

    // PhoneTech Platform
    Route::middleware(['permission:access phonetech'])->prefix('phonetech')->name('phonetech.')->group(function () {
        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        
        // Sales
        Route::resource('sales', SaleController::class);
        Route::post('sales/{sale}/payments', [SaleController::class, 'addPayment'])->name('sales.add-payment');
        Route::get('sales/export', [SaleController::class, 'export'])->name('sales.export');
        Route::post('sales/bulk-reminders', [SaleController::class, 'bulkReminders'])->name('sales.bulk-reminders');
        
       
    // Suppliers - إضافة routes الموردين المفقودة
    Route::resource('suppliers', App\Http\Controllers\PhoneTech\SupplierController::class);
    Route::post('suppliers/import', [App\Http\Controllers\PhoneTech\SupplierController::class, 'import'])
        ->name('suppliers.import');
    Route::get('suppliers/export', [App\Http\Controllers\PhoneTech\SupplierController::class, 'export'])
        ->name('suppliers.export');
        // Installments
        Route::resource('installments', InstallmentController::class)->only(['index', 'show']);
        Route::get('installments/{sale}/schedule', [InstallmentController::class, 'schedule'])->name('installments.schedule');
        Route::post('installments/{sale}/payment', [InstallmentController::class, 'addPayment'])->name('installments.add-payment');
        Route::get('installments/overdue/list', [InstallmentController::class, 'overdue'])->name('installments.overdue');
        Route::post('installments/{sale}/reminder', [InstallmentController::class, 'sendReminder'])->name('installments.send-reminder');
        Route::get('installments/{sale}/print-schedule', [InstallmentController::class, 'printSchedule'])->name('installments.print-schedule');
        Route::post('installments/send-bulk-reminders', [InstallmentController::class, 'sendBulkReminders'])->name('installments.send-bulk-reminders');
        Route::get('installments/export', [InstallmentController::class, 'export'])->name('installments.export');
    });

    // Car Import Platform
    Route::middleware(['permission:access carimport'])->prefix('carimport')->name('carimport.')->group(function () {
        Route::resource('imports', CarImportController::class);
        Route::post('imports/bulk-update', [CarImportController::class, 'bulkUpdate'])->name('imports.bulk-update');
        Route::get('imports/export', [CarImportController::class, 'export'])->name('imports.export');
        Route::post('imports/{carImport}/update-status', [CarImportController::class, 'updateStatus'])->name('imports.update-status');
    });

    // Admin Panel
    Route::middleware(['permission:access admin'])->prefix('admin')->name('admin.')->group(function () {
        // Users & Roles
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::post('roles/bulk-assign', [RoleController::class, 'bulkAssign'])->name('roles.bulk-assign');
        Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export');
        Route::post('roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('roles.assign-users');
        Route::post('roles/{role}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user');
        Route::get('roles/{role}/export-details', [RoleController::class, 'exportDetails'])->name('roles.export-details');
        
        // Accounting System
        Route::resource('accounts', AccountController::class);
        Route::get('accounts/journal-entries', [AccountController::class, 'journalEntries'])->name('accounts.journal-entries');
        Route::get('accounts/create-journal-entry', [AccountController::class, 'createJournalEntry'])->name('accounts.create-journal-entry');
        Route::post('accounts/store-journal-entry', [AccountController::class, 'storeJournalEntry'])->name('accounts.store-journal-entry');
        Route::get('accounts/trial-balance', [AccountController::class, 'trialBalance'])->name('accounts.trial-balance');
        Route::get('accounts/balance-sheet', [AccountController::class, 'balanceSheet'])->name('accounts.balance-sheet');
        Route::get('accounts/income-statement', [AccountController::class, 'incomeStatement'])->name('accounts.income-statement');
        Route::get('accounts/ledger', [AccountController::class, 'ledger'])->name('accounts.ledger');
        Route::get('accounts/export', [AccountController::class, 'export'])->name('accounts.export');

        // إدارة المواعيد
        Route::resource('appointments', AppointmentController::class);
        Route::get('appointments/calendar/view', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
    });

    // Reports
    Route::middleware(['permission:view reports'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/campaigns', [ReportController::class, 'campaigns'])->name('campaigns');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/car-imports', [ReportController::class, 'carImports'])->name('car-imports');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });
    
    // Exports
    Route::middleware(['permission:view reports'])->prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::post('/sales/excel', [ExportController::class, 'salesExcel'])->name('sales.excel');
        Route::post('/sales/pdf', [ExportController::class, 'salesPdf'])->name('sales.pdf');
        Route::post('/products/excel', [ExportController::class, 'productsExcel'])->name('products.excel');
        Route::post('/campaigns/excel', [ExportController::class, 'campaignsExcel'])->name('campaigns.excel');
        Route::post('/car-imports/excel', [ExportController::class, 'carImportsExcel'])->name('car-imports.excel');
        Route::post('/installments/excel', [ExportController::class, 'installmentsExcel'])->name('installments.excel');
        Route::post('/profit-loss/pdf', [ExportController::class, 'profitLossPdf'])->name('profit-loss.pdf');
    });
    
    // Notifications
    Route::middleware(['permission:access admin'])->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/send', [NotificationController::class, 'send'])->name('send');
    });
    
    // Security
    Route::middleware(['permission:access admin'])->prefix('security')->name('security.')->group(function () {
        Route::get('/', [SecurityController::class, 'index'])->name('index');
        Route::get('/activity-log', [SecurityController::class, 'activityLog'])->name('activity-log');
        Route::get('/login-attempts', [SecurityController::class, 'loginAttempts'])->name('login-attempts');
        Route::get('/settings', [SecurityController::class, 'settings'])->name('settings');
        Route::post('/update-settings', [SecurityController::class, 'updateSettings'])->name('update-settings');
        Route::post('/change-password', [SecurityController::class, 'changePassword'])->name('change-password');
        Route::post('/enable-2fa', [SecurityController::class, 'enable2FA'])->name('enable-2fa');
        Route::post('/disable-2fa', [SecurityController::class, 'disable2FA'])->name('disable-2fa');
        Route::post('/logout-all-devices', [SecurityController::class, 'logoutAllDevices'])->name('logout-all-devices');
    });
});

require __DIR__.'/auth.php';
