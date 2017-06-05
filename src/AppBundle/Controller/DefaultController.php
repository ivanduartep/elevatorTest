<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Elevator;
use AppBundle\Entity\Floor;
use AppBundle\Entity\Game;
use AppBundle\Form\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('player')
            ->add('elevators', NumberType::class)
            ->add('floors', NumberType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $player = $data['player'];
            $elevators = intval($data['elevators']);
            $floors = intval($data['floors']);

            if(strlen($player) < 1){
                throw new HttpException(400, "Name is not valid.");
            }

            if($elevators <= 1 || $elevators >= 8){
                throw new HttpException(400, "Elevators number is not valid.");
            }

            if($floors <= 1 || $floors > 12){
                throw new HttpException(400, "Floors number is not valid.");
            }

            $em = $this->getDoctrine()->getManager();

            $game = new Game();
            $game->setPlayer($player);

            for($i = 0; $i<$floors; $i++){
                $floor = new Floor();
                $floor->setGame($game);
                $game->addFloor($floor);
                if($i == 0){
                    $floor->setName('Ground');
                }else{
                    $floor->setName($i+1);
                }
                $floor->setNumber($i+1);
                $em->persist($floor);
                $em->persist($game);
            }

            for($i = 0; $i<$elevators; $i++){
                $elevator = new Elevator();
                $elevator->setNumber($i+1);
                $elevator->setStatus('ready');
                $elevator->setFloor($game->getFloors()[0]);
                $elevator->setDirection('stand');
                $game->getFloors()[0]->addElevator($elevator);
                $game->addElevator($elevator);
                $elevator->setGame($game);
                $em->persist($elevator);
                $em->persist($game);
                $em->persist($game->getFloors()[0]);
            }

            $em->flush();

            return $this->redirectToRoute("game_index", array("id" => $game->getId()));

        }


        return $this->render('default/main.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
