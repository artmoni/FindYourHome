<?php

namespace App\Repository;

use App\Entity\Accomodation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Accomodation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accomodation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accomodation[]    findAll()
 * @method Accomodation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccomodationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Accomodation::class);
    }

    public function getAccomodationAverageMarks($accomodationId)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->join('a.reviews', 'r');
        $qb->where('a.id = ' . $accomodationId);
        $qb->select($qb->expr()->avg('r.mark'));
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function searchAccomodation($search) {
        $qb = $this->createQueryBuilder('a');
        $qb->join('a.location', 'l');
        $qb->where($qb->expr()->orX(
            $qb->expr()->like('LOWER(a.name)', '?1'),
            $qb->expr()->like('LOWER(l.country)', '?1'),
            $qb->expr()->like('LOWER(l.city)', '?1')
        ));
        $qb->setParameter('1', '%' . strtolower($search) . '%');
        $qb->select('a');
        return $qb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return Accomodation[] Returns an array of Accomodation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Accomodation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
