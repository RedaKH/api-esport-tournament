<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in_progress';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';


}

?>