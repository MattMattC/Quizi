<?php

namespace MQ\QuiziBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MQ\QuiziBundle\Entity\Question;
use MQ\QuiziBundle\Entity\Quiz;
use MQ\QuiziBundle\Entity\Reponse;
use MQ\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadQuizzData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $quiz = new Quiz();

        $quiz->setTitreQuiz("nom quizz");
        $quiz->setAffichageFinalQuiz("truc");

        date_default_timezone_set('UTC');

        $date = date('d-m-Y');
        $quiz->setDateCreationQuiz(new \DateTime($date));
        $quiz->setUser($this->getReference('user_admin'));

        // Ajout du quiz dans la BDD
        $manager->persist($quiz);
        $manager->flush();

        // Les Questions
        $question = new Question();
        $question->setTitreQuestion("Quelle mangeur de fraise est le plus connu ?");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();

        $reponse1->setTitreReponse("la réponse A jamy !");
        $reponse2->setTitreReponse("mieux ! la réponse B!");
        $reponse2->setBonneReponse(1);
        $reponse1->setBonneReponse(0);

        $question->addReponse($reponse1);
        $question->addReponse($reponse2);

        $manager->persist($reponse1);
        $manager->persist($reponse2);

        $manager->persist($question);
        $manager->flush();

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
        return 2;
    }

}

?>
