<?php

namespace App\Repository;

use App\Entity\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Email>
 */
class EmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
    }

        public function findByNotYetSended(): ?Email
        {
            return $this->createQueryBuilder('e')
                ->andWhere('e.sended = :val')
                ->setParameter('val', Email::NOT_SENDED)
                ->orderBy('e.createdAt', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Email
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
