<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

class ApplicationType extends Enum
{
    const NEW = 'new';
    const RECON = 'recon';
    const METER_SEPARATION = 'meter_separation';
    const OTHERS = 'others';
}
