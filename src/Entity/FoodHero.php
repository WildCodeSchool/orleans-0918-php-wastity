<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FoodHeroRepository")
 */
class FoodHero
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
     *     message = "Veuillez renseigner un numéro de téléphone pour l'association")
     * @Assert\Regex("/[0-9 +-\.]+/",
     *     message = "Le numéro de téléphone '{{ value }}' n'est pas valide")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le nombre de caractères maximum est de 255"
     * )
     */
    private $phone;

    public function getId(): ?int
    {
        return $this->id;
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
}
