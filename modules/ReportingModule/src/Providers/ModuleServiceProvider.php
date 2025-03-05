<?php

namespace Kolette\Reporting\Providers;

use App\Support\Models;
use Kolette\Reporting\Http\Resources\ReportCategoriesResource;
use Kolette\Reporting\Http\Resources\ReportResource;
use Kolette\Reporting\Models\Report;
use Kolette\Reporting\Models\ReportCategories;
use Konekt\Concord\BaseBoxServiceProvider;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->registerResources();
    }

    public function boot(): void
    {
        parent::boot();
    }

    protected function registerResources(): void
    {
        Models::registerModel(Report::class, ReportResource::class, 'reports');
        Models::registerModel(ReportCategories::class, ReportCategoriesResource::class, 'report_categories');
    }
}
