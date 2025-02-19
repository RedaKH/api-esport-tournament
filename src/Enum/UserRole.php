<?php
namespace App\Enum;

 enum UserRole : string
 {
    case ADMIN = 'ROLE_ADMIN';
    case ORGANIZER = 'ROLE_ORGANIZER';
    case MANAGER = 'ROLE_MANAGER';
    case PLAYER = 'ROLE_PLAYER';
    case SPONSOR = 'ROLE_SPONSOR';
 }

?>