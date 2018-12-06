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
     * @ORM\Column(type="time", nullable=true)
     * @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     */
    private $openingAM;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     * @Assert\GreaterThan(propertyPath="openingAM",
     *     message="Votre heure de fermeture ne doit pas être antérieure à votre heure d'ouverture")
     */
    private $closingAM;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     *  @Assert\GreaterThanOrEqual(propertyPath="closingAM",
     *     message="Votre heure d\'ouverture ne doit pas être antérieure à votre heure de fermeture du matin")
     */
    private $openingPM;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\Time(
     *     message="Le format de l'heure doit être HH:MM")
     * @Assert\GreaterThan(propertyPath="openingPM",
     *     message="Votre heure de fermeture ne doit pas être antérieure à votre heure d'ouverture")
     */
    private $closingPM;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=true)
     */
    private $company;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\DaysOfWeek", inversedBy="schedules", fetch="EAGER")
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="schedules")
     */
    private $association;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpeningAM(): ?\DateTime
    {
        return $this->openingAM;
    }

    public function setOpeningAM(?\DateTime $openingAM): self
    {
        $this->openingAM = $openingAM;

        return $this;
    }

    public function getClosingAM(): ?\DateTime
    {
        return $this->closingAM;
    }

    public function setClosingAM(?\DateTime $closingAM): self
    {
        $this->closingAM = $closingAM;

        return $this;
    }

    public function getOpeningPM(): ?\DateTime
    {
        return $this->openingPM;
    }

    public function setOpeningPM(?\DateTime $openingPM): self
    {
        $this->openingPM = $openingPM;

        return $this;
    }

    public function getClosingPM(): ?\DateTime
    {
        return $this->closingPM;
    }

    public function setClosingPM(?\DateTime $closingPM): self
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

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }
}
