<?php

namespace App\Validator;

use App\Entity\DragonTreasure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TreasuresAllowedOwnerChangeValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param DragonTreasure[] $value
     * @param TreasuresAllowedOwnerChange $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $hasTriedToSteel = false;
        foreach ($value as $dragonTreasure) {
            $originalData = $this->entityManager->getUnitOfWork()->getOriginalEntityData($dragonTreasure);

            if (!$originalData['owner_id'] || $originalData['owner_id'] === $dragonTreasure->getOwner()?->getId()) {
                continue;
            }

            $hasTriedToSteel = true;
            break;
        }

        if ($hasTriedToSteel) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
