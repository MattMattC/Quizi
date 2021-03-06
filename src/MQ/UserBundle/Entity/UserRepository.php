<?php

namespace MQ\UserBundle\Entity;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 * @property  User
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public $User;

    /**
     * @param $role
     * @return array
     */
    public function findByRole($role) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->User, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');
        return $qb->getQuery()->getResult();
    }
}
