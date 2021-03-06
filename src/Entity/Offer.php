<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 * @Vich\Uploadable()
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
     */
    private $picture;

    /**
     * @Vich\UploadableField(mapping="offer", fileNameProperty="picture")
     * @var File
     * @Assert\File(
     *     maxSize = "2048k",
     *     maxSizeMessage = "Veuillez ajouter une image de moins de 2 Mo",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Veuillez ajouter un fichier jpg,jpeg ou png"
     * )
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function setPictureFile(File $image = null) : void
    {
        $this->pictureFile = $image;
        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank(
     *     message="Le poids ne peut pas être vide")
     *
     * @Assert\GreaterThan(0,
     *     message="Le poids doit être supérieur à 0kg")
     *
     * @Assert\Type("numeric"),
     *     message="Le poids n'est pas valide")
     * )
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotNull(message="La date de début doit être obligatoirement renseignée")
     *
     * @Assert\DateTime(format="Y-m-d H:i",
     *     message="Votre date doit etre de la forme AAAA-MM-JJ HH:MM")
     *
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     *
     *
     * @Assert\NotNull(message="La date de fin doit être obligatoirement renseignée")
     * @Assert\DateTime(format="Y-m-d H:i",
     *     message="Votre date doit etre de la forme AAAA-MM-JJ HH:MM")
     * @Assert\GreaterThanOrEqual(propertyPath="start",
     *     message="Votre date de fin ne doit pas être antérieure à la date de début")
     * @Assert\Range(
     *      min = "now",
     *      minMessage="La date et l'heure de fin de l'offre doivent être supérieures à la date et l'heure actuelles"
     *  )
     */
    private $end;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     *
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $complementary;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="offers",  fetch="EAGER")
     */
    private $association;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="offers",  fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FoodHero", inversedBy="offers",  fetch="EAGER")
     */
    private $foodhero;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
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


    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;
    
        return $this;
    }
    
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany($company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    public function getFoodhero(): ?FoodHero
    {
        return $this->foodhero;
    }

    public function setFoodhero(?FoodHero $foodhero): self
    {
        $this->foodhero = $foodhero;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
