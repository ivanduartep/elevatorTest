<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 6/4/17
 * Time: 11:44 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Request as Rqst;
use AppBundle\Entity\Floor;


/**
 * @Route("/elevator")
 */
class ElevatorController extends Controller
{

    /**
     * @Route("/move", name="elevator_move")
     * @Method("POST")
     */
    public function moveAction(){
        $gameId = $_POST['gameId'];
        $movedElevators = [];
        $movedElevatorsInFloor = [];
        $elevatorsWhitOpenDoors = [];

        $em = $this->getDoctrine()->getEntityManager();

        $elevators = $em->getRepository("AppBundle:Elevator")
            ->findBy(array("game" => $gameId));


        foreach($elevators as $elevator) {

            if (count($elevator->getToFloors()) > 0) {

                if ($elevator->getFloor()->getNumber() == $elevator->getToFloors()[0] && $elevator->getDirection() != "stand") {
                    $elevator->removeFirstToFloors();
                    array_push($elevatorsWhitOpenDoors, $elevator->getFloor()->getNumber());
                }
            }

            if (count($elevator->getToFloors()) == 0) {
                $elevator->setDirection("stand");
                $elevator->setStatus("ready");
            }

            if (count($elevator->getToFloors()) > 0) {

                $newDirection = $elevator->getFloor()->getNumber() < $elevator->getToFloors()[0] ? "up" : "down";
                $elevator->setDirection($newDirection);

                $floor = $elevator->getFloor();
                $floor->removeElevator($elevator);
                $floorNumber = $floor->getNumber();
                $incOrDec = ($newDirection == "up") ? 1 : -1;
                $elevator->setFloor(null);
                $newFloor = $em->getRepository("AppBundle:Floor")
                    ->findOneBy(array(
                        "game" => $gameId,
                        "number" => $floorNumber + $incOrDec));
                $newFloor->addElevator($elevator);
                $elevator->setFloor($newFloor);
                $em->persist($newFloor);
                $em->persist($floor);
                //$em->persist($elevator);
                array_push($movedElevators, $elevator->getNumber());
                array_push($movedElevatorsInFloor, $elevator->getFloor()->getNumber());
            }

            $em->persist($elevator);
            $em->flush();
        }
            return new JsonResponse(array(
                "elevatorsNumber" => $movedElevators,
                "elevatorsNewPosition" => $movedElevatorsInFloor,
                "elevatorsInFloor" => $elevatorsWhitOpenDoors
            ));

    }

}