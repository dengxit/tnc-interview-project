<?php

namespace App\Repository;

use App\Entity\User;
use App\QueryParams\UserQueryParams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
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

    public function findByQueryParams(UserQueryParams $queryParams): \Doctrine\ORM\Query
    {
        $data = $queryParams->getData();
        $queryBuilder = $this->createQueryBuilder('u');
        if (!empty($data['user_type'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('u.user_type',':user_type'))
                ->setParameter('user_type', $data['user_type']);
        }

        if (isset($data['is_active'])) {
            $queryBuilder->andWhere('u.is_active = :is_active')
                ->setParameter('is_active', $data['is_active']);
        }

        if (isset($data['is_member'])) {
            $queryBuilder->andWhere('u.is_member = :is_member')
                ->setParameter('is_member', $data['is_member']);
        }

        if (isset($data['last_login_start_at'])) {
            $queryBuilder->andWhere('u.last_login_at >= :last_login_start_at')
                ->setParameter('last_login_start_at', $data['last_login_start_at']);
        }

        if (isset($data['last_login_end_at'])) {
            $queryBuilder->andWhere('u.last_login_at <= :last_login_end_at')
                ->setParameter('last_login_end_at', $data['last_login_end_at']);
        }

        return $queryBuilder->getQuery();
    }
}
