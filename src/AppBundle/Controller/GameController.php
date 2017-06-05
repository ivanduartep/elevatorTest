<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 5/31/17
 * Time: 9:55 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Elevator;
use AppBundle\Entity\Floor;
use AppBundle\Entity\Game;
use AppBundle\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/{id}", name="game_index")
     * @Template()
     */
    public function indexAction($id){

        $em = $this->getDoctrine();

        $game = $em->getRepository("AppBundle:Game")
            ->find($id);

        return$this->render('Game/index.html.twig', array(
            'game' => $game
        ));

    }

}