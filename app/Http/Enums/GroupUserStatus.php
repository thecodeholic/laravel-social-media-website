<?php
/**
 * User: Zura
 * Date: 12/9/2023
 * Time: 12:48 AM
 */

namespace App\Http\Enums;

enum GroupUserStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
