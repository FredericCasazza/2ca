<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 */
class Meal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $bookDateLimit;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $published = false;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Period")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     */
    private $period;

    /**
     * @ORM\ManyToMany(targetEntity="Establishment")
     * @ORM\JoinTable(name="meal_establishment",
     *     joinColumns={@ORM\JoinColumn(name="meal_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="establishement_id", referencedColumnName="id")}
     * )
     */
    private $establishments;

    /**
     * @ORM\OneToMany(targetEntity="Dish", mappedBy="meal", cascade={"persist"})
     */
    private $dishes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="meal", orphanRemoval=true)
     */
    private $orders;

    /**
     * Meal constructor.
     */
    public function __construct()
    {
        $this->establishments = new ArrayCollection();
        $this->dishes = new ArrayCollection();
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
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBookDateLimit()
    {
        return $this->bookDateLimit;
    }

    /**
     * @param mixed $bookDateLimit
     * @return Meal
     */
    public function setBookDateLimit($bookDateLimit)
    {
        $this->bookDateLimit = $bookDateLimit;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param bool $published
     * @return Meal
     */
    public function setPublished(bool $published): Meal
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     * @return Meal
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * @param mixed $modificationDate
     * @return Meal
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
        return $this;
    }

    /**
     * @return Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param Period $period
     * @return Meal
     */
    public function setPeriod(Period $period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEstablishments(): Collection
    {
        return $this->establishments;
    }

    /**
     * @param Collection $establishments
     * @return $this
     */
    public function setEstablishments(Collection $establishments): self
    {
        $this->establishments = $establishments;

        return $this;
    }

    /**
     * @param Establishment $establishment
     * @return $this
     */
    public function addEstablishment(Establishment $establishment): self
    {
        if(!$this->establishments->contains($establishment))
        {
            $this->establishments[] = $establishment;
        }

        return $this;
    }

    /**
     * @param Establishment $establishment
     * @return $this
     */
    public function removeEstablishment(Establishment $establishment): self
    {
        if($this->establishments->contains($establishment))
        {
            $this->establishments->removeElement($establishment);
        }

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
     * @return $this
     */
    public function setDishes(Collection $dishes): self
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
            $dish->setMeal($this);
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
            // set the owning side to null (unless already changed)
            if ($dish->getMeal() === $this) {
                $dish->setMeal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setMeal($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getMeal() === $this) {
                $order->setMeal(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strftime('%A %d %B %Y', $this->getDate()->getTimestamp()).' '.$this->getPeriod()->getLabel();
    }

    /**
     *
     */
    public function __clone()
    {
        $dishes = $this->getDishes();
        $this->dishes = new ArrayCollection();
        foreach ($dishes as $dish)
        {
            $this->addDish(clone  $dish);
        }
    }
}
