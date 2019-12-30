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
     * @ORM\ManyToMany(targetEntity="Dish")
     * @ORM\JoinTable(name="order_dish",
     *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="dish_id", referencedColumnName="id")}
     *    )
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
     * @return mixed
     */
    public function getValidationDate()
    {
        return $this->validationDate;
    }

    /**
     * @param mixed $validationDate
     * @return Order
     */
    public function setValidationDate($validationDate)
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Order
     */
    public function setUser($user)
    {
        $this->user = $user;
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
        }

        return $this;
    }
}
