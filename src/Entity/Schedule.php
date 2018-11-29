<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $openingAM;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $closingAM;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $openingPM;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $closingPM;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DaysOfWeek", inversedBy="schedules")
     */
    private $day;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpeningAM(): ?int
    {
        return $this->openingAM;
    }

    public function setOpeningAM(?int $openingAM): self
    {
        $this->openingAM = $openingAM;

        return $this;
    }

    public function getClosingAM(): ?int
    {
        return $this->closingAM;
    }

    public function setClosingAM(?int $closingAM): self
    {
        $this->closingAM = $closingAM;

        return $this;
    }

    public function getOpeningPM(): ?int
    {
        return $this->openingPM;
    }

    public function setOpeningPM(?int $openingPM): self
    {
        $this->openingPM = $openingPM;

        return $this;
    }

    public function getClosingPM(): ?int
    {
        return $this->closingPM;
    }

    public function setClosingPM(?int $closingPM): self
    {
        $this->closingPM = $closingPM;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDay(): ?DaysOfWeek
    {
        return $this->day;
    }

    public function setDay(?DaysOfWeek $day): self
    {
        $this->day = $day;

        return $this;
    }
}
