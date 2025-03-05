<?php

namespace Kolette\Media\Models\Interfaces;

use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

interface HasMedia extends SpatieHasMedia
{
    public function defaultCollectionName(): string;
}
