<?php

namespace Kolette\Auth\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ErrorCodes extends Enum implements LocalizedEnum
{
    const UNVERIFIED_EMAIL = 'UNVERIFIED_EMAIL';
    const UNVERIFIED_PHONE_NUMBER = 'UNVERIFIED_PHONE_NUMBER';
    const UNVERIFIED_ACCOUNT = 'UNVERIFIED_ACCOUNT';
    const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
    const INVALID_ONE_TIME_PASSWORD = 'INVALID_ONE_TIME_PASSWORD';
    const AUTHENTICATION_REQUIRED = 'AUTHENTICATION_REQUIRED';
    const AUTHENTICATION_EMAIL_REQUIRED = 'AUTHENTICATION_EMAIL_REQUIRED';
    const AUTHENTICATION_PHONE_NUMBER_REQUIRED = 'AUTHENTICATION_PHONE_NUMBER_REQUIRED';
    const USING_OLD_PASSWORD = 'USING_OLD_PASSWORD';
    const INVALID_USERNAME = 'INVALID_USERNAME';
    const INVALID_PASSWORD = 'INVALID_PASSWORD';
    const USERNAME_NOT_FOUND = 'USERNAME_NOT_FOUND';
    const EMAIL_NOT_FOUND = 'EMAIL_NOT_FOUND';
    const ACCOUNT_BLOCKED = 'ACCOUNT_BLOCKED';
    const PASSWORD_NOT_SUPPORTED = 'PASSWORD_NOT_SUPPORTED';
    const TOKEN_NOT_FOUND = 'TOKEN_NOT_FOUND';
    public const MEDIA_EXCEEDS_MAX_FILE_SIZE = 'MEDIA_EXCEEDS_MAX_FILE_SIZE';
    public const UNAUTHORIZED_ACTION = 'UNAUTHORIZED_ACTION';
}
