<?php

namespace Kolette\Reporting\Models;

use Kolette\Media\Models\Media;
use Kolette\Reporting\Factories\IssueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Issue extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_ATTACHMENTS = 'attachments';

    protected $fillable = [
        'reported_by',
        'subject',
        'description',
    ];

    protected static function newFactory(): IssueFactory
    {
        return IssueFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_ATTACHMENTS)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
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
}
