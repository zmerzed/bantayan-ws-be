<?php

namespace Kolette\Reporting\Models;

use Kolette\Reporting\Factories\ReportCategoriesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCategories extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): ReportCategoriesFactory
    {
        return ReportCategoriesFactory::new();
    }
}
