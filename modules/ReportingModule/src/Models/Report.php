<?php

namespace Kolette\Reporting\Models;

use Kolette\Auth\Models\User;
use Kolette\Media\Models\Media;
use Kolette\Reporting\Factories\ReportFactory;
use Kolette\Reporting\Models\Traits\InteractsWithReportableTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Report extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use InteractsWithReportableTypes;

    public const MEDIA_COLLECTION_ATTACHMENTS = 'attachments';

    protected $fillable = [
        'reported_by',
        'description',
    ];

    protected $appends = [
        'report_type',
    ];

    protected static function newFactory(): ReportFactory
    {
        return ReportFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_ATTACHMENTS)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }


    public function reason(): BelongsTo
    {
        return $this->belongsTo(ReportCategories::class, 'reason_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Media::class, 'model_id')
            ->where('collection_name', self::MEDIA_COLLECTION_ATTACHMENTS);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getReportTypeAttribute(): string
    {
        $type = array_search($this->reportable_type, array_merge($this->getReportableTypes(), Relation::$morphMap));

        if ($type !== false) {
            return $type;
        }

        return $this->reportable_type;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeHasReportType(Builder $query, $types): void
    {
        $query->hasMorph('reportable', Arr::only($this->getReportableTypes(), $types));
    }

    /**
     * @todo Improve Search performance for a large dataset.
     */
    public function scopeSearch(Builder $query, string $search): void
    {
        $query->whereHasMorph('reportable', User::class, function ($query) use ($search) {
            $query->search($search);
        });

        $query->orWhereHas('reporter', function ($query) use ($search) {
            $query->search($search);
        });
    }
}
