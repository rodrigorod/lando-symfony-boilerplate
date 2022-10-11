<?php

namespace App\Repository;

use App\Api\Car\Entity\Car;
use App\Api\Car\Entity\CarInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method null|Car find($id, $lockMode = null, $lockVersion = null)
 * @method null|Car findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function add(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get all cars who have the same category.
     *
     * @return array<CarInterface>
     */
    public function getCarsByCategory(string $slug): array
    {
        return $this->createQueryBuilder('cars')
            ->addSelect('cat')
            ->leftJoin('cars.categories', 'cat')
            ->where('cat.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult()
        ;
    }
}
