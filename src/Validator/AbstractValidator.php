<?php

declare(strict_types=1);

namespace App\Validator;

use App\Dto\DtoInterface;
use App\Validator\Components\ValidatorComponentInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

readonly abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @param SymfonyValidatorInterface $validator
     */
    public function __construct(
        protected SymfonyValidatorInterface $validator,
    ) {
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return array<int, string>
     */
    protected function prepareErrors(ConstraintViolationListInterface $errors): array
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }

    /**
     * @param ValidatorComponentInterface $component
     * @return array<int, string>
     */
    protected function checkComponent(ValidatorComponentInterface $component): array
    {
        $results = [];
        foreach ($component->getDtoConstraints() as $dto) {
            $errors = $this->validator->validate($dto->value, $dto->constraints);
            if (0 !== $errors->count()) {
                $results = [...$results, ...$this->prepareErrors($errors)];
            }
        }

        return $results;
    }

    /**
     * @param DtoInterface $dto
     * @return bool
     */
    abstract protected function checkDtoInstance(DtoInterface $dto): bool;
}
