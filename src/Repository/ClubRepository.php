<?php

namespace App\Repository;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubInterface;
use App\Api\User\Entity\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 *
 * @method null|Club find($id, $lockMode = null, $lockVersion = null)
 * @method null|Club findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function add(Club $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Club $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get all clubs who have the same category.
     *
     * @return array<ClubInterface>
     */
    public function getClubsByCategory(string $slug): array
    {
        return $this->createQueryBuilder('club')
            ->addSelect('cat')
            ->leftJoin('club.categories', 'cat')
            ->where('cat.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult()
        ;
    }
}
