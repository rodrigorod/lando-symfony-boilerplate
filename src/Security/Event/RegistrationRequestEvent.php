<?php

namespace App\Security\Event;

use App\Api\User\Entity\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class RegistrationRequestEvent extends Event
{
    public const NAME = 'user.registration.request';

    protected UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
