<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner le type de l'entreprise")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le nombre de caractères maximum est de 255"
     * )
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner le nom de l'entreprise")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le nombre de caractères maximum est de 255"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner l'adresse de l'entreprise")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le nombre de caractères maximum est de 255"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un code postal pour l'entreprise")
     * @Assert\Regex("/[0-9]{5}/",
     *     message = "Le code postal'{{ value }}' n'est pas valide")
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Le code postal doit contenir {{ limit }} chiffres"
     * )
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner la ville de l'entreprise")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le nombre de caractères maximum est de 255"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un numéro de téléphone pour l'entreprise")
     * @Assert\Regex("/[0-9 +-\.]+/",
     *     message = "Le numéro de téléphone '{{ value }}' n'est pas valide")
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Votre numéro de téléphone doit contenir {{ limit }} chiffres"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un email pour l'entreprise")
     * @Assert\Email(
     *     message = "Le mail '{{ value }}' n'est pas un mail valide",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="company")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offers;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="company", cascade={"persist"})
     */
    private $schedules;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }


    /**
     * @param $type
     * @return Company
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param $name
     * @return Company
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * @param $address
     * @return Company
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }


    /**
     * @param $postalCode
     * @return Company
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }


    /**
     * @param $city
     * @return Company
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }


    /**
     * @param $phone
     * @return Company
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * @param $email
     * @return Company
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getOffers(): Collection
    {
        return $this->offers;

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setCompany($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getCompany() === $this) {
                $schedule->setCompany(null);
            }
        }

        return $this;
    }
}
