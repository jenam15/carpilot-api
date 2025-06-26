<?php

namespace App\DTO\User;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangePasswordDto
{
    #[Assert\NotBlank(message: "This field cannot be blank.")]
    public ?string $currentPassword = null;
    #[Assert\NotBlank(message: "This field cannot be blank.")]
    public ?string $newPassword = null;
    #[Assert\EqualTo(
        propertyPath: 'newPassword',
        message: 'New password and password confirmation should match.'
    )]
    #[Assert\NotBlank(message: "This field cannot be blank.")]
    public ?string $confirmPassword = null;

}