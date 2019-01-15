<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FoodHeroRepository")
 * @Vich\Uploadable()
 */
class FoodHero implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un numéro de téléphone")
     * @Assert\Regex("/[0-9 +-\.]+/",
     *     message = "Le numéro de téléphone '{{ value }}' n'est pas valide")
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le nombre de caractères maximum est de 20"
     * )
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="foodHero")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profileImage;

    /**
     * @Vich\UploadableField(mapping="profile", fileNameProperty="profileImage")
     * @var File
     * @Assert\NotBlank(message="L'image doit etre renseignée")
     * @Assert\File(
     *     maxSize = "2048k",
     *     maxSizeMessage = "Veuillez ajouter une image de moins de 2 Mo",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Veuillez ajouter un fichier image"
     * )
     */
    private $profileImageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="foodhero")
     */
    private $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    public function setProfileImageFile(File $image = null) : void
    {
        $this->profileImageFile = $image;
        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }
    public function getProfileImageFile()
    {
        return $this->profileImageFile;
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage($profileImage): self
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setFoodhero($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getFoodhero() === $this) {
                $offer->setFoodhero(null);
            }
        }

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->profileImage,

        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,

            ) = unserialize($serialized);
    }
}
