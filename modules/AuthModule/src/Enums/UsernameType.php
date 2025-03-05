<?php

namespace Kolette\Auth\Enums;

use BenSampo\Enum\Enum;

final class UsernameType extends Enum
{
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phone_number';
}
