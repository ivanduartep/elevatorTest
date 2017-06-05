<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
/**
 * Elevator
 *
 * @ORM\Table(name="elevator")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElevatorRepository")
 */
class Elevator
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="direction", type="string", length=255)
     */
    private $direction;

    /**
     * Many Elevators have One Floor.
     * @ManyToOne(targetEntity="Floor", inversedBy="elevators")
     * @JoinColumn(name="floor_id", referencedColumnName="id")
     */
    private $floor;

    /**
     * Many Elevators have One Game.
     * @ManyToOne(targetEntity="Game", inversedBy="elevators")
     * @JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    public function __construct()
    {
        $this->toFloors = array();
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
     * @var array
     *
     * @ORM\Column(name="toFloors", type="array")
     */
    private $toFloors;

    /**
     * @return mixed
     */
    public function getFloor()
    {
        return $this->floor;
    }

    public function removeFirstToFloors(){
        array_shift($this->toFloors);
    }

    /**
     * @param mixed $floor
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
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
     * Set number
     *
     * @param integer $number
     *
     * @return Elevator
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
     * Set status
     *
     * @param string $status
     *
     * @return Elevator
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set direction
     *
     * @param string $direction
     *
     * @return Elevator
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @return array
     */
    public function getToFloors()
    {
        return $this->toFloors;
    }

    public function addToFloors($toFloor){
        array_push($this->toFloors, $toFloor);
    }

    /**
     * @param array $toFloors
     */
    public function setToFloors($toFloors)
    {
        $this->toFloors = $toFloors;
    }
}

