<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(max="255", maxMessage="La constante ne peut etre supérieure à 255 caractères")
     * @ORM\Column(type="string", length=255)
     */
    private $constStatus;

    /**
     * @Assert\Regex("/#[a-zA-Z0-9]{6}/",
     *     message="Votre couleur ne correspond pas au format requis, il doit etre de forme '#aaaaaa'")
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @Assert\Length(max="255", maxMessage="Le texte ne peut etre supérieur à 255 caractères")
     * @ORM\Column(type="string", length=255)
     */
    private $statusText;

    /**
     * @Assert\Length(max="255", maxMessage="La classe Font-Awesome ne peut etre supérieure à 255 caractères")
     * @ORM\Column(type="string", length=255)
     */
    private $classFontAwesome;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConstStatus(): ?string
    {
        return $this->constStatus;
    }

    public function setConstStatus(string $constStatus): self
    {
        $this->constStatus = $constStatus;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getStatusText(): ?string
    {
        return $this->statusText;
    }

    public function setStatusText(string $statusText): self
    {
        $this->statusText = $statusText;

        return $this;
    }

    public function getClassFontAwesome(): ?string
    {
        return $this->classFontAwesome;
    }

    public function setClassFontAwesome(string $classFontAwesome): self
    {
        $this->classFontAwesome = $classFontAwesome;

        return $this;
    }
}
