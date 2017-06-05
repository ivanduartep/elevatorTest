<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Floor
 *
 * @ORM\Table(name="floor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FloorRepository")
 */
class Floor
{

    public function __construct() {
        $this->requests = new ArrayCollection();
        $this->elevators = new ArrayCollection();
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * Many Floors have One Game.
     * @ManyToOne(targetEntity="Game", inversedBy="floors")
     * @JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * One Floor has Many Requests.
     * @OneToMany(targetEntity="Request", mappedBy="floor")
     */
    private $requests;

    /**
     * One Floor has Many Elevators.
     * @OneToMany(targetEntity="Elevator", mappedBy="floor")
     */
    private $elevators;

    /**
     * @return mixed
     */
    public function getElevators()
    {
        return $this->elevators;
    }

    /**
     * @param mixed $elevators
     */
    public function setElevators($elevators)
    {
        $this->elevators = $elevators;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Floor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Floor
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param $request
     */
    public function addRequest($request)
    {
        if (!$this->getRequests()->contains($request)) {
            $this->getRequests()->add($request);
        }
    }

    /**
     * @param mixed $requests
     */
    public function setRequests($requests)
    {
        $this->requests = $requests;
    }

    public function addElevator($elevator)
    {
        if (!$this->getElevators()->contains($elevator)) {
            $this->getElevators()->add($elevator);
        }
    }

    public function removeElevator($elevatorToRemove)
    {
        $this->elevators->removeElement($elevatorToRemove);
    }
}

