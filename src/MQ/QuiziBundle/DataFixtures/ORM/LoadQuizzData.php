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

        /*
         *
         * QUIZ 1 --> QUI VEUT GAGNER DE L'ARGENT EN MASSE ?
         *
        */

        // Ajout du quiz dans la BDD
        $quiz = new Quiz();

        $quiz->setTitreQuiz("Qui veut gagner d'l'argent en masse ?");
        $quiz->setAffichageFinalQuiz("3");

        date_default_timezone_set('UTC');

        $date = date('d-m-Y');
        $quiz->setDateCreationQuiz(new \DateTime($date));
        $quiz->setUser($this->getReference('user'));

        // Ajout du quiz dans la BDD
        $manager->persist($quiz);
        $manager->flush();

        // --------------------------------------------------------------
        // QUESTION 1
        $question = new Question();
        $question->setTitreQuestion("Lorsqu'un pancake tombe dans la neige avant le 31 décembre, on dit qu'il est :");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("tombé dans la neige avant le 31 décembre");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("un frizby comestible");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("une kipa surgelée");
        $reponse3->setBonneReponse(1);
        $reponse4->setTitreReponse("la réponse D");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();


        // --------------------------------------------------------------
        // QUESTION 2
        $question = new Question();
        $question->setTitreQuestion("Lorsqu'un pancake prend l'avion à destination de Toronto et qu'il fait une escale technique à St Claude, on dit :");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("qu'il n'est pas arrivé à Toronto");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("qu'il était supposé arriver à Toronto");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("qu'est ce qu'il fout ce maudit pancake tabernacle ?");
        $reponse3->setBonneReponse(1);
        $reponse4->setTitreReponse("la réponse D");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();

        // --------------------------------------------------------------
        // QUESTION 3
        $question = new Question();
        $question->setTitreQuestion("Lorsqu'on invite un pancake à une Barmitzva, les convivent doivent :");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("l'inciter à boire à l'Open Barmitzva");
        $reponse1->setBonneReponse(1);
        $reponse2->setTitreReponse("lui présenter Raymond Barmitzva");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("lui offrir des Malabarmitzva");
        $reponse3->setBonneReponse(0);
        $reponse4->setTitreReponse("la réponse D");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();

        // --------------------------------------------------------------
        // QUESTION 4
        $question = new Question();
        $question->setTitreQuestion("Au cours de quel évènement historique fut créé le pancake ?");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("En 1618, pendant la guerre des croissants au beurre");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("En 1702, pendant le massacre de la Saint Panini");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("En 112 avant Céline, pendant la prise de la brioche");
        $reponse3->setBonneReponse(1);
        $reponse4->setTitreReponse("la réponse D");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();


        /*
         *
         * QUIZ 2 --> LES MATHS POUR LES NULS !
         *
        */

        // Ajout du quiz dans la BDD
        $quiz = new Quiz();

        $quiz->setTitreQuiz("Les Maths pour les nuls !");
        $quiz->setAffichageFinalQuiz("1");

        date_default_timezone_set('UTC');

        $date = date('d-m-Y');
        $quiz->setDateCreationQuiz(new \DateTime($date));
        $quiz->setUser($this->getReference('user'));

        // Ajout du quiz dans la BDD
        $manager->persist($quiz);
        $manager->flush();

        // --------------------------------------------------------------
        // QUESTION 1
        $question = new Question();
        $titreQuestion = "<svg width=\"400\" height=\"110\"><rect width=\"300\" height=\"100\" style=\"fill:rgb(0,0,255);stroke-width:3;stroke:rgb(0,0,0)\">Sorry, your browser does not support inline SVG.</svg>";
        $question->setTitreQuestion("Que represente cette forme : <br>" . $titreQuestion);
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("Un carré");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("Un cercle");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("Un triangle");
        $reponse3->setBonneReponse(0);
        $reponse4->setTitreReponse("Un rectangle");
        $reponse4->setBonneReponse(1);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();

        // --------------------------------------------------------------
        // QUESTION 2
        $question = new Question();
        $question->setTitreQuestion("Qu'est ce qu'un triangle ?");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("<svg width=\"400\" height=\"110\"><rect width=\"300\" height=\"100\" style=\"fill:rgb(0,0,255);stroke-width:3;stroke:rgb(0,0,0)\">Sorry, your browser does not support inline SVG.</svg>");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("<svg height=\"100\" width=\"100\"><circle cx=\"50\" cy=\"50\" r=\"40\" stroke=\"black\" stroke-width=\"3\" fill=\"red\" />Sorry, your browser does not support inline SVG.</svg>");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("<svg height=\"210\" width=\"400\"><path d=\"M150 0 L75 200 L225 200 Z\" />Sorry, your browser does not support inline SVG.</svg>");
        $reponse3->setBonneReponse(1);
        $reponse4->setTitreReponse("<svg height=\"210\" width=\"500\"><polygon points=\"100,10 40,198 190,78 10,78 160,198\" style=\"fill:lime;stroke:purple;stroke-width:5;fill-rule:nonzero;\"/>Sorry, your browser does not support inline SVG.</svg>");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();


        // --------------------------------------------------------------
        // QUESTION 3
        $question = new Question();
        $titreQuestion = "<math display=\"block\"><mrow><mfrac><mrow><mi>4</mi><mo>+</mo><msup><mi>4</mi><mn>2</mn></msup></mrow><mrow><mi>2</mi><mo>+</mo><mn>3</mn></mrow></mfrac></mrow></math>";
        $question->setTitreQuestion("Quel est le résultat de " . $titreQuestion);
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("2");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("4");
        $reponse2->setBonneReponse(1);
        $reponse3->setTitreReponse("5");
        $reponse3->setBonneReponse(0);
        $reponse4->setTitreReponse("10");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

        $manager->persist($question);
        $manager->flush();



        // --------------------------------------------------------------
        // QUESTION 4
        $question = new Question();
        $question->setTitreQuestion("A quoi est égal 5 ?");
        $question->setQuiz($quiz);

        // Les réponses
        $reponse1 = new Reponse();
        $reponse2 = new Reponse();
        $reponse3 = new Reponse();
        $reponse4 = new Reponse();

        $reponse1->setTitreReponse("<math display=\"block\"><mrow><mfrac><mrow><mi>6</mi><mo>+</mo><msup><mi>4</mi><mn>2</mn></msup></mrow><mrow><mi>2</mi><mo>+</mo><mn>3</mn></mrow></mfrac></mrow></math>");
        $reponse1->setBonneReponse(0);
        $reponse2->setTitreReponse("<math display=\"block\"><mrow><mfrac><mrow><mi>4</mi><mo>+</mo><msup><mi>4</mi><mn>2</mn></msup></mrow><mrow><mi>2</mi><mo>+</mo><mn>3</mn></mrow></mfrac></mrow></math>");
        $reponse2->setBonneReponse(0);
        $reponse3->setTitreReponse("<math display=\"block\"><mrow><mfrac><mrow><mi>4</mi><mo>+</mo><msup><mi>4</mi><mn>2</mn></msup></mrow><mrow><mi>2</mi><mo>+</mo><mn>2</mn></mrow></mfrac></mrow></math>");
        $reponse3->setBonneReponse(1);
        $reponse4->setTitreReponse("<math display=\"block\"><mrow><mfrac><mrow><mi>4</mi><mo>+</mo><msup><mi>4</mi><mn>2</mn></msup></mrow><mrow><mi>-1</mi><mo>+</mo><mn>3</mn></mrow></mfrac></mrow></math>");
        $reponse4->setBonneReponse(0);


        $question->addReponse($reponse1);
        $question->addReponse($reponse2);
        $question->addReponse($reponse3);
        $question->addReponse($reponse4);

        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);

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
