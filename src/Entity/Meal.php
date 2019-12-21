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
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $published = false;

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
     * @ORM\OneToMany(targetEntity="Dish", mappedBy="meal")
     */
    private $dishes;

    /**
     * Meal constructor.
     */
    public function __construct()
    {
        $this->establishments = new ArrayCollection();
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
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     * @return Meal
     */
    public function setPeriod($period)
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
            $this->establishments->add($establishment);
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
            $this->dishes->add($dish);
        }

        return $this;
    }

    /**
     * @param Dish $dish
     * @return $this
     */
    public function removeDish(Dish $dish): self
    {
        if($this->dishes->contains($dish))
        {
            $this->dishes->removeElement($dish);
        }

        return $this;
    }
}
