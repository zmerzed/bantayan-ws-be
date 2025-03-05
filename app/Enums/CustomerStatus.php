<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

class CustomerStatus extends Enum
{
    const ACTIVE = 'active';
    const CLOSED = 'closed';
    const PENDING = 'pending';
}
