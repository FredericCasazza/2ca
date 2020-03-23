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
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cette adresse mail est déjà utilisée"
 * )
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=60, nullable=false)
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "Le nom doit comporter {{ limit }} lettres minimum"
     * )
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=60, nullable=false)
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "Le prénom doit comporter {{ limit }} lettres minimum"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string|null
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Le mot de passe doit contenir {{ limit }} caractères minimum"
     * )
     */
    private $plainTextPassword;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $reinitToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reinitExpirationDate;

    /**
     * @var Establishment|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Establishment")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $establishment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user", orphanRemoval=true)
     */
    private $orders;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($this->roles, $role);
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role): self
    {
        if(!in_array($role, $this->roles))
        {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
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
     * @return string|null
     */
    public function getPlainTextPassword(): ?string
    {
        return $this->plainTextPassword;
    }

    /**
     * @param string|null $plainTextPassword
     * @return User
     */
    public function setPlainTextPassword(?string $plainTextPassword): User
    {
        $this->plainTextPassword = $plainTextPassword;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReinitToken(): ?string
    {
        return $this->reinitToken;
    }

    /**
     * @param string|null $reinitToken
     * @return $this
     */
    public function setReinitToken(?string $reinitToken)
    {
        $this->reinitToken = $reinitToken;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getReinitExpirationDate(): ?\DateTimeInterface
    {
        return $this->reinitExpirationDate;
    }

    /**
     * @param \DateTimeInterface|null $reinitExpirationDate
     * @return $this
     */
    public function setReinitExpirationDate(?\DateTimeInterface $reinitExpirationDate)
    {
        $this->reinitExpirationDate = $reinitExpirationDate;
        return $this;
    }

    /**
     * @return Establishment|null
     */
    public function getEstablishment(): ?Establishment
    {
        return $this->establishment;
    }

    /**
     * @param Establishment|null $establishment
     * @return User
     */
    public function setEstablishment( $establishment)
    {
        $this->establishment = $establishment;
        return $this;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}
