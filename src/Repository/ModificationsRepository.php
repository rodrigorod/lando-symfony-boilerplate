<?php

namespace App\Repository;

use App\Api\Car\Entity\Modifications;
use App\Api\Post\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method null|Modifications find($id, $lockMode = null, $lockVersion = null)
 * @method null|Modifications findOneBy(array $criteria, array $orderBy = null)
 * @method Modifications[]    findAll()
 * @method Modifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modifications::class);
    }

    public function add(Modifications $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Modifications $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}