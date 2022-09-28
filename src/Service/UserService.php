<?php

namespace App\Service;

use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Service\GarageService;
use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use App\DependencyInjection\TokenHelperAwareTrait;
use App\Security\Exception\TokenExceptionInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserService
{
    use TokenHelperAwareTrait;

    public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $passwordHasher,
        protected GarageService $garageService,
    ) {}

    /**
     * Register a new user & creates an empty Garage.
     *
     * @param array $credentials
     *  Credentials
     *
     * @return UserInterface
     *  User
     */
    public function registerUser(array $credentials): UserInterface
    {
        $user = new User(['username' => $credentials['username'], 'email' => $credentials['email']]);
        $user->setPassword($this->passwordHasher->hashPassword($user, $credentials['password']));

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $user;
    }

    /**
     * Activate user by:
     * - validating secured token
     * - activating user
     * - removing activation token request (secured link won't work again).
     *
     * @param string $token
     *  Secured token
     * @throws Exception
     */
    public function activateUser(string $token): UserInterface
    {
        try {
            /** @var UserInterface $user */
            $user = $this->tokenHelper->validateTokenAndFetchUser($token);
        } catch (TokenExceptionInterface $e) {
            throw new AuthenticationException(
                sprintf('There was a problem validating the request - %s', $e->getReason()),
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user->setActive(true)
            ->setActivatedAt(new DateTime())
        ;

        try {
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception(sprintf('An error occurred : %s', $e->getMessage()));
        }

        // Activation token can be used only once, remove it.
        $this->tokenHelper->removeTokenRequest($token);

        return $user;
    }
}
