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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $constKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $classColorName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statusText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConstKey(): ?string
    {
        return $this->constKey;
    }

    public function setConstKey(string $constKey): self
    {
        $this->constKey = $constKey;

        return $this;
    }

    public function getClassColorName(): ?string
    {
        return $this->classColorName;
    }

    public function setClassColorName(string $classColorName): self
    {
        $this->classColorName = $classColorName;

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
}
