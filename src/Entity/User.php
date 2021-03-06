<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity("email", message="L'email est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(message="Votre email doit être renseigné")
     * @Assert\Email(message="Vous devez entrer un email", checkMX=true)
     *
     *
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez renseigner un mot de passe")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un prénom")
     *
     * @Assert\Length(max="255", maxMessage="Votre nom doit contenir au maximum {{limit}} caractères")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Vous devez renseigner un prénom")
     *
     * @Assert\Length(max="255", maxMessage="Votre nom doit contenir au maximum {{limit}} caractères")
     */
    private $lastname;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\FoodHero", mappedBy="user")
     */
    private $foodHero;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Association", mappedBy="user")
     */
    private $association;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Company", mappedBy="user", cascade={"persist", "remove"})
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Association", mappedBy="members")
     */
    private $memberAssociations;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Company", mappedBy="members")
     */
    private $memberCompanies;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $activate;

    public function __construct()
    {
        $this->memberAssociations = new ArrayCollection();
        $this->memberCompanies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
  
    public function getFoodHero(): ?FoodHero
    {
        return $this->foodHero;
    }

    public function setFoodHero(FoodHero $foodHero): self
    {
        $this->foodHero = $foodHero;

        // set the owning side of the relation if necessary
        if ($this !== $foodHero->getUser()) {
            $foodHero->setUser($this);
        }
        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        // set the owning side of the relation if necessary
        if ($this !== $company->getUser()) {
            $company->setUser($this);
        }

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(Association $association): self
    {
        $this->association = $association;

        // set the owning side of the relation if necessary
        if ($this !== $association->getUser()) {
            $association->setUser($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullname(): ?string
    {
        return $this->fullname = ucfirst($this->getLastname()). ' ' .ucfirst($this->getFirstname());
    }

    /**
     * @return Collection|Association[]
     */
    public function getMemberAssociations(): Collection
    {
        return $this->memberAssociations;
    }

    public function addMemberAssociation(Association $memberAssociation): self
    {
        if (!$this->memberAssociations->contains($memberAssociation)) {
            $this->memberAssociations[] = $memberAssociation;
            $memberAssociation->addMember($this);
        }

        return $this;
    }
  
    public function removeMemberAssociation(Association $memberAssociation): self
    {
        if ($this->memberAssociations->contains($memberAssociation)) {
            $this->memberAssociations->removeElement($memberAssociation);
            $memberAssociation->removeMember($this);
        }

        return $this;
    }
          
    /**
     * @return Collection|Company[]
     */
    public function getMemberCompanies(): Collection
    {
        return $this->memberCompanies;
    }

    public function addMemberCompany(Company $memberCompany): self
    {
        if (!$this->memberCompanies->contains($memberCompany)) {
            $this->memberCompanies[] = $memberCompany;
            $memberCompany->addMember($this);
        }

        return $this;
    }

          
    public function removeMemberCompany(Company $memberCompany): self
    {
        if ($this->memberCompanies->contains($memberCompany)) {
            $this->memberCompanies->removeElement($memberCompany);
            $memberCompany->removeMember($this);
        }

        return $this;
    }

    public function getActivate(): ?bool
    {
        return $this->activate;
    }

    public function setActivate(bool $activate): self
    {
        $this->activate = $activate;

        return $this;
    }
}
