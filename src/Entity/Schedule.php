<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
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
     *  @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     */
    private $openingAM;

    /**
     *  @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     * @Assert\GreaterThan(propertyPath="openingAM",
     *     message="Votre heure de fermeture ne doit pas être antérieure à votre heure d'ouverture")
     */
    private $closingAM;

    /**
     *  @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     *  @Assert\GreaterThanOrEqual(propertyPath="closingAM",
     *     message="Votre heure d\'ouverture ne doit pas être antérieure à votre heure de fermeture du matin")
     */
    private $openingPM;

    /**
     * @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     * @Assert\GreaterThan(propertyPath="openingPM",
     *     message="Votre heure de fermeture ne doit pas être antérieure à votre heure d'ouverture")
     */
    private $closingPM;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=true)
     */
    private $company;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\DaysOfWeek", inversedBy="schedules", fetch="EAGER")
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
