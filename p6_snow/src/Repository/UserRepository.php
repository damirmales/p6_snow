<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    //**
    // * @return User[] Returns an array of User objects
    // */
    /*
        public function findByUsername($value)
        {

            try {
                return $this->createQueryBuilder('u')
                    // ->select('u.email')
                    ->andWhere('u.username = :val')
                    ->setParameter('val', $value)
                    ->getQuery()
                    ->getSingleResult();
            } catch (NoResultException $e) {

            } catch (NonUniqueResultException $e) {

            }
        }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
