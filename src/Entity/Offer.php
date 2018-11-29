<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message = "Veuillez mettre une image de vos produits")
     *
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *     message="Le poids ne peut pas être vide")
     *
     * @Assert\GreaterThan(0,
     *     message="Le poids doit être supérieur à 0kg")
     *
     *  @Assert\Type("integer"),
     *     message="Le poids n'est pas valide")
     * )
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime(format="Y-m-d H:i",
     *     message="Votre date doit etre de la forme AAAA-MM-JJ HH:MM")
     *
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime(format="Y-m-d H:i",
     *     message="Votre date doit etre de la forme AAAA-MM-JJ HH:MM")
     * @Assert\GreaterThanOrEqual(propertyPath="start",
     *     message="Votre date de fin ne doit pas être antérieure à la date de début")
     */
    private $end;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $complementary;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getComplementary(): ?string
    {
        return $this->complementary;
    }

    public function setComplementary(?string $complementary): self
    {
        $this->complementary = $complementary;

        return $this;
    }
}
