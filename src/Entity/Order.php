<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Meal", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Establishment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $establishment;

    /**
     * @ORM\ManyToMany(targetEntity="Dish", mappedBy="orders")
     */
    private $dishes;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->dishes = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return Order
     */
    public function setComment($comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTimeInterface $creationDate
     * @return $this
     */
    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    /**
     * @param \DateTimeInterface $modificationDate
     * @return $this
     */
    public function setModificationDate(\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidationDate()
    {
        return $this->validationDate;
    }

    /**
     * @param \DateTimeInterface $validationDate
     * @return Order
     */
    public function setValidationDate(\DateTimeInterface $validationDate)
    {
        $this->validationDate = $validationDate;
        return $this;
    }

    /**
     * @return Meal|null
     */
    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    /**
     * @param Meal|null $meal
     * @return $this
     */
    public function setMeal(?Meal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Order
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
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
     * @param Establishment $establishment
     * @return $this
     */
    public function setEstablishment(Establishment $establishment): self
    {
        $this->establishment = $establishment;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    /**
     * @param Collection $dishes
     * @return Order
     */
    public function setDishes(Collection $dishes): Order
    {
        $this->dishes = $dishes;
        return $this;
    }

    /**
     * @param Dish $dish
     * @return $this
     */
    public function addDish(Dish $dish): self
    {
        if(!$this->dishes->contains($dish))
        {
            $this->dishes[] = $dish;
            $dish->addOrder($this);
        }

        return $this;
    }

    /**
     * @param Dish $dish
     * @return $this
     */
    public function removeDish(Dish $dish): self
    {
        if ($this->dishes->contains($dish)) {
            $this->dishes->removeElement($dish);
            $dish->removeOrder($this);
        }

        return $this;
    }
}
