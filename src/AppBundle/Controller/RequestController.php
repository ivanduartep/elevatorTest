<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 6/4/17
 * Time: 8:46 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Request as Rqst;
use AppBundle\Entity\Floor;
use AppBundle\Entity\Elevator;

/**
 * @Route("/request")
 */
class RequestController extends Controller
{

    /**
     * @Route("/create", name="request_create")
     * @Method("POST")
     */
    public function createAction(Request $request){
        $direction = $_POST['direction'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $time = $_POST['time'];

        $request = new Rqst();

        if($direction != "" && $from != "" && $to != "" && $time != ""){
            $em = $this->getDoctrine()->getEntityManager();

            $floor = $em->getRepository("AppBundle:Floor")
                ->find($_POST['floorId']);

            if(!$floor){
                return new JsonResponse(array(
                    "result" => false,
                    "message" => "Error in the data"
                ));
            }

            $request->setDirection($direction);
            $request->setTime($time);
            $request->setToFloor($to);
            $request->setFromFloor($from);
            $request->setFloor($floor);
            $floor->addRequest($request);

            $em->persist($request);
            $em->persist($floor);
            $em->flush();

        }else{
            return new JsonResponse(array(
                "result" => false,
                "message" => "Error in the data"
            ));
        }


        return new JsonResponse(array(
            "result" => true,
            "message" => "New Elevator call created",
            "requestId" => $request->getId(),
            "requestFrom" => $request->getFromFloor(),
            "requestTo" => $request->getToFloor(),
            "requestTime" => $request->getTime()
        ));
    }

    /**
     * @Route("/serve", name="request_serve")
     * @Method("POST")
     */
    public function serveAction(){
        $requestId = $_POST['requestId'];
        $from = $_POST['requestFrom'];
        $to = $_POST['requestTo'];

        $em = $this->getDoctrine()->getEntityManager();

        $request = $em->getRepository("AppBundle:Request")
            ->find($requestId);

        if(!$request){
            return new JsonResponse(array(
                "result" => false,
                "message" => "Error in the data"
            ));
        }

        $floor = $request->getFloor();

        if(!$floor){
            return new JsonResponse(array(
                "result" => false,
                "message" => "Error in the data"
            ));
        }

        $correctElevator = null;

        $direction = $from < $to ? "up" : "down";
        $elevatorsInFloor = $floor->getElevators();

        if(count($elevatorsInFloor) > 0){

            //The first priority is the elevator standing in the floor
            foreach ($elevatorsInFloor as $elevator){
                if($elevator->getStatus() == "ready"){
                    $correctElevator = $elevator;
                    break;
                }
            }
        }

        if($correctElevator == null) {

            $elevatorsInSameDirection = $em->getRepository("AppBundle:Elevator")
                ->findBy(array("direction" => $direction, "game" => $floor->getGame()->getId()));

            foreach ($elevatorsInSameDirection as $elevator) {
                if ($elevator->getFloor()->getNumber() > $from && $elevator->getDirection() == "up") {
                    $correctElevator = $elevator;
                    break;
                } elseif ($elevator->getFloor()->getNumber() < $from && $elevator->getDirection() == "down") {
                    $correctElevator = $elevator;
                    break;
                }
            }
        }
                if ($correctElevator == null) {

                    $elevatorsStanding = $em->getRepository("AppBundle:Elevator")
                        ->findBy(array("status" => "ready", "game" => $floor->getGame()->getId()));

                    $bestRange = 13;

                    //the third priority is the nearest standing elevator
                    foreach ($elevatorsStanding as $anotherElevator) {
                        if (abs($anotherElevator->getFloor()->getNumber() - $from) < $bestRange) {
                            $bestRange = abs($anotherElevator->getFloor()->getNumber() - $from);
                            $correctElevator = $anotherElevator;
                        }
                    }

                }


        if($correctElevator != null) {
            $correctElevator->setStatus("on move");
            if ($correctElevator->getFloor()->getNumber() != $from) {
                $correctElevator->addToFloors($from);
            }
            $correctElevator->addToFloors($to);

            $direction = $correctElevator->getFloor()->getNumber() < $correctElevator->getToFloors()[0] ? "up" : "down";
            $correctElevator->setDirection($direction);

            $em->persist($correctElevator);
            $em->flush();

            return new JsonResponse(array(
                "elevatorNumber"  => $correctElevator->getNumber()
            ));
        }else{
            return new JsonResponse(array(
                "result" => false
            ));
        }
    }

}