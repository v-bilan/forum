<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CityConstraint extends Constraint
{
    public const CITY_CONSTRAINT_ERROR = '88011c04-fffd-47fc-a575-ea442f4d03a2';

    protected const ERROR_NAMES = [
        self::CITY_CONSTRAINT_ERROR => 'CITY_CONSTRAINT_ERROR',
    ];

    public $message = 'Please select a valid city.';
}