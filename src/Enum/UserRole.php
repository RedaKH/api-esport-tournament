<?php
namespace App\Enum;

 enum UserRole : string
 {
    case ADMIN = 'ADMIN';
    case ORGANIZER = 'ORGANIZER';
    case TEAM_MANAGER = 'ROLE_TEAM_MANAGER';
    case PLAYER = 'ROLE_PLAYER';
    case SPONSOR = 'ROLE_SPONSOR';
 }

?>