<?php

namespace Kolette\Auth\Enums;

use BenSampo\Enum\Enum;

final class Permission extends Enum
{
    const ASSIGN_ROLE = 'ASSIGN_ROLE';
    const ADD_ROLE = 'ADD_ROLE';
    const DELETE_ROLE = 'DELETE_ROLE';

    const VIEW_USERS = 'VIEW_USERS';
    const UPDATE_USERS = 'UPDATE_USERS';
    const DELETE_USERS = 'DELETE_USERS';

    const VIEW_MERCHANTS = 'VIEW_MERCHANTS';
    const UPDATE_MERCHANTS = 'UPDATE_MERCHANTS';
    const DELETE_MERCHANTS = 'DELETE_MERCHANTS';

    const VIEW_ORDERS = 'VIEW_ORDERS';
}
