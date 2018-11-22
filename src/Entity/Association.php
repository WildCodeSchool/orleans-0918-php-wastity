<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssociationRepository")
 */
class Association
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
     *     message = "Veuillez renseigner le champ du formulaire")
     * @Assert\Regex("/^[a-zA-Zéèàïëê ]+$/i",
     *     message = "Le nom '{{ value }}' n'est pas valide")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner le champ du formulaire")
     * @Assert\Regex("/^[a-zA-Zéèàïëê0-9 ]+$/i",
     *     message = "L'adresse '{{ value }}' n'est pas valide")
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner le champ du formulaire")
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
     *     message = "Veuillez renseigner le champ du formulaire")
     * @Assert\Regex("/^[a-zA-Zéèàïëê ]+$/i",
     *     message = "La ville'{{ value }}' n'est pas valide")
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner le champ du formulaire")
     * @Assert\Regex("/(0)[1-9][0-9]{8}/",
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
     *     message = "Veuillez renseigner le champ du formulaire")
     * @Assert\Email(
     *     message = "Le mail '{{ value }}' n'est pas un mail valide",
     *     checkMX = true
     * )
     */
    private $mail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }
}
