<?php
/**
 * User: Zura
 * Date: 12/9/2023
 * Time: 12:49 AM
 */

namespace App\Http\Enums;

enum GroupUserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
