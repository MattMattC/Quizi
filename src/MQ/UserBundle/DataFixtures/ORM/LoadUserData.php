<?php

namespace MQ\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MQ\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

    class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();

        // Attribution des attributs
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        $userAdmin->setUsername('admin');
        $userAdmin->setMail('admin@admin.fr');

        // Encodage du mot de passe
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($userAdmin, 'admin');
        $userAdmin->setPassword($encoded);

        // Enregistrement sur la BDD
        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference('user_admin', $userAdmin);

        $user = new User();

        // Attribution des attributs
        $user->setRoles(array('ROLE_USER'));
        $user->setUsername('user');
        $user->setMail('user@user.fr');

        // Encodage du mot de passe
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, 'user');
        $user->setPassword($encoded);

        // Enregistrement sur la BDD
        $manager->persist($user);
        $manager->flush();

        $this->addReference("user", $user);
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container=$container;
    }

    // Ordre d'execution des fixtures
    public function getOrder()
    {
        return 1;
    }

}

?>
