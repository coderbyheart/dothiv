<?php

namespace Dothiv\BusinessBundle\Repository;

use Dothiv\BusinessBundle\Entity\User;
use PhpOption\Option;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;

class UserRepository extends DoctrineEntityRepository implements UserRepositoryInterface
{
    /**
     * @param string $email
     *
     * @return Option
     */
    public function getUserByEmail($email)
    {
        return Option::fromValue(
            $this->createQueryBuilder('u')
                ->andWhere('u.email = :email')->setParameter('email', strtolower($email))
                ->getQuery()
                ->getOneOrNullResult()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $user)
    {
        $this->getEntityManager()->persist($user);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
        return $this;
    }
}