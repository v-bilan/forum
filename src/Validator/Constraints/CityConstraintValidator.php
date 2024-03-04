<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CityConstraintValidator extends ConstraintValidator
{

     public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof CityConstraint) {
            throw new UnexpectedTypeException($constraint, CityConstraint::class);
        }


        $cities = $this->getCities();
        if (!in_array(strtolower($value), $cities)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(CityConstraint::CITY_CONSTRAINT_ERROR)
                ->addViolation();
        }
    }
    private function getCities() {
         return [
             'lviv',
             'kyiv'
         ];
    }
}