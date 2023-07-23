<?php

namespace App\Validator;

use App\Entity\User;
use App\QueryParams\UserQueryParams;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class UserQueryParamsValidator
{
    public function validate(UserQueryParams $queryParams): array
    {
        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
            'fields' => [
                'is_active' => new Assert\Optional([
                    new Assert\Choice(['choices' => User::IS_ACTIVE_ARRAY]),
                ]),
                'is_member' => new Assert\Optional([
                    new Assert\Choice(['choices' => User::IS_MEMBER_ARRAY]),
                ]),
                'user_type' => new Assert\All([
                    new Assert\Choice(['choices' => User::USER_TYPE_ARRAY]),
                ]),
                'last_login_start_at' => new Assert\Optional([
                    new Assert\DateTime(),
                ]),
                'last_login_end_at' => new Assert\Optional([
                    new Assert\DateTime(),
                ]),
            ],
        ]);

        $violations = $validator->validate($queryParams, $constraints);

        return $this->formatViolations($violations);
    }

    private function formatViolations(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $property = $violation->getPropertyPath();
            $message = $violation->getMessage();
            $errors[$property] = $message;
        }

        return $errors;
    }
}
