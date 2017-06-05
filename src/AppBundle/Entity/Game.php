<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
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
     * @var string
     *
     * @ORM\Column(name="player", type="string", length=255)
     */
    private $player;

    /**
     * One Game has Many Floors.
     * @OneToMany(targetEntity="Floor", mappedBy="game")
     */
    private $floors;

    /**
     * One Game has Many Elevators.
     * @OneToMany(targetEntity="Elevator", mappedBy="game")
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

    public function addElevator($elevator)
    {
        if (!$this->getElevators()->contains($elevator)) {
            $this->getElevators()->add($elevator);
        }
    }


    public function __construct() {
        $this->floors = new ArrayCollection();
        $this->elevators = new ArrayCollection();
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
     * Set player
     *
     * @param string $player
     *
     * @return Game
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return string
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return mixed
     */
    public function getFloors()
    {
        return $this->floors;
    }

    /**
     * @param mixed $floors
     */
    public function setFloors($floors)
    {
        $this->floors = $floors;
    }

    public function addFloor($floor)
    {
        if (!$this->getFloors()->contains($floor)) {
            $this->getFloors()->add($floor);
        }
    }


}

