<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=255)
     */
    private $constStatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statusText;

    /**
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
