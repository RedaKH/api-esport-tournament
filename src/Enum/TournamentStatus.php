<?php

enum TournamentStatus: string
{
    case DRAFT = 'draft';
    case REGISTRATION = 'registration';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';


}

?>