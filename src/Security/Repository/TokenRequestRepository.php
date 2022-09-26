<?php

namespace App\Security\Repository;

use App\Security\Entity\TokenRequest;
use App\Security\Entity\TokenRequestInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|TokenRequest find($id, $lockMode = null, $lockVersion = null)
 * @method null|TokenRequest findOneBy(array $criteria, array $orderBy = null)
 * @method TokenRequest[]    findAll()
 * @method TokenRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRequestRepository extends ServiceEntityRepository implements TokenRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokenRequest::class);
    }

    public function createTokenRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): TokenRequestInterface
    {
        return new TokenRequest($user, $expiresAt, $selector, $hashedToken);
    }

    public function getUserIdentifier(object $user): string
    {
        return $this->getEntityManager()
            ->getUnitOfWork()
            ->getSingleIdentifierValue($user)
            ;
    }

    public function persistTokenRequest(TokenRequestInterface $resetPasswordRequest): void
    {
        $this->getEntityManager()->persist($resetPasswordRequest);
        $this->getEntityManager()->flush();
    }

    public function findTokenRequest(string $selector): ?TokenRequestInterface
    {
        return $this->findOneBy(['selector' => $selector]);
    }

    public function getMostRecentNonExpiredRequestDate(object $user): ?\DateTimeInterface
    {
        // Normally there is only 1 max request per use, but written to be flexible
        /** @var TokenRequestInterface $resetPasswordRequest */
        $resetPasswordRequest = $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.requestedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneorNullResult()
        ;

        if (null !== $resetPasswordRequest && !$resetPasswordRequest->isExpired()) {
            return $resetPasswordRequest->getRequestedAt();
        }

        return null;
    }

    public function removeTokenRequest(object $user): void
    {
        $this->createQueryBuilder('t')
            ->delete()
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute()
        ;
    }

    public function removeExpiredTokenRequests(): int
    {
        $time = new DateTimeImmutable('-1 week');
        $query = $this->createQueryBuilder('t')
            ->delete()
            ->where('t.expiresAt <= :time')
            ->setParameter('time', $time)
            ->getQuery()
        ;

        return $query->execute();
    }
}
