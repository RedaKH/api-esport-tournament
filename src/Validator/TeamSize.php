<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]

class TeamSize extends Constraint
{
    public string $message = 'Une équipe doit avoir entre {{min}} et {{max}} joueurs';
    public int $min = 2;
    public int $max = 10;
}


?>