<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Request
 *
 * @ORM\Table(name="request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RequestRepository")
 */
class Request
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
     * @ORM\Column(name="fromFloor", type="integer")
     */
    private $fromFloor;

    /**
     * @var int
     *
     * @ORM\Column(name="toFloor", type="integer")
     */
    private $toFloor;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="direction", type="string", length=255)
     */
    private $direction;


    /**
     * Many Requests have One Floor.
     * @ManyToOne(targetEntity="Floor", inversedBy="requests")
     * @JoinColumn(name="floor_id", referencedColumnName="id")
     */
    private $floor;

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
     * Set fromFloor
     *
     * @param integer $fromFloor
     *
     * @return Request
     */
    public function setFromFloor($fromFloor)
    {
        $this->fromFloor = $fromFloor;

        return $this;
    }

    /**
     * Get fromFloor
     *
     * @return int
     */
    public function getFromFloor()
    {
        return $this->fromFloor;
    }

    /**
     * Set toFloor
     *
     * @param integer $toFloor
     *
     * @return Request
     */
    public function setToFloor($toFloor)
    {
        $this->toFloor = $toFloor;

        return $this;
    }

    /**
     * Get toFloor
     *
     * @return int
     */
    public function getToFloor()
    {
        return $this->toFloor;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Request
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set direction
     *
     * @param string $direction
     *
     * @return Request
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
     * @return mixed
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param mixed $floor
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    }
}

