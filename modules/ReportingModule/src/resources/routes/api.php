<?php

use Kolette\Reporting\Http\Controllers\V1\Admin\ReportController as AdminReportController;
use Kolette\Reporting\Http\Controllers\V1\Admin\UserReportController;
use Kolette\Reporting\Http\Controllers\V1\IssueController;
use Kolette\Reporting\Http\Controllers\V1\ReportCategoriesController;
use Kolette\Reporting\Http\Controllers\V1\ReportController;
use Illuminate\Support\Facades\Route;


Route::post('issues', IssueController::class)->name('issue.store');
Route::post('report', ReportController::class)->name('report.store');
Route::get('report/categories', [ReportCategoriesController::class, 'index'])->name('report.categories');
Route::get('refund/reasons', [ReportCategoriesController::class, 'refundReasons'])->name('refund.reasons');

Route::prefix('admin')
    ->group(
        function () {
            Route::get('reports', AdminReportController::class)
                ->name('admin.reports');
            Route::get('report/users', UserReportController::class)
                ->name('admin.report.users.index');
        }
    );
