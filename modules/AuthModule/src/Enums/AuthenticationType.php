<?php

namespace Kolette\Auth\Enums;

use BenSampo\Enum\Enum;

final class AuthenticationType extends Enum
{
    const OTP = 'otp';
    const PASSWORD = 'password';
}
