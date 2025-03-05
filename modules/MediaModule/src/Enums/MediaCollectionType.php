<?php

namespace Kolette\Media\Enums;

use BenSampo\Enum\Enum;

final class MediaCollectionType extends Enum
{
    const UNASSIGNED = 'unassigned';
    const AVATAR = 'avatar';
    const ATTACHMENT = 'attachment';
    const QRCODE = 'qrcode';
}
