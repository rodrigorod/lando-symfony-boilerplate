<?php

namespace App\Security\Event;

use Symfony\Contracts\EventDispatcher\Event;

class RegistrationSuccessEvent extends Event
{
    public const NAME = 'user.registration.success';
}
