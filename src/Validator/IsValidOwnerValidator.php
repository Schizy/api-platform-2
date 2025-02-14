<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidOwnerValidator extends ConstraintValidator
{
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param User|null $value
     * @param IsValidOwner $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (is_null($value) || $value === $this->security->getUser() || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
