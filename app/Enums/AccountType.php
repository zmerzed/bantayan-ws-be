<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

class AccountType extends Enum
{
    const RESIDENCE = 'residence';
    const COMMERCIAL = 'commercial';
    const APARTMENT = 'apartment';
    const MARKET_STALL = 'market_stall';
    const OTHERS = 'others';
}
