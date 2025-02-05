<?php
namespace App\Validator;


use App\Entity\Team;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[\Attribute]

class TeamSizeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void{
        if (!$value instanceof Team) {
            return;
        }
    

    $playersCount = $value->getPlayers->count();
    if ($playersCount < $constraint->min || $playersCount > $constraint->max) {

        $this->context->buildViolation($constraint->message)->setParameters(
            [
                '{{min}}' => $constraint->min,
                '{{max}}' => $constraint->max


            ]
        )
        ->addViolation();
        # code...
    }
}
}


?>