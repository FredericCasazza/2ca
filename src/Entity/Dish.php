<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DishRepository")
 */
class Dish
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $label;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $visible = true;

    /**
     * @var DishCategory
     * @ORM\ManyToOne(targetEntity="DishCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @var Meal
     * @ORM\ManyToOne(targetEntity="Meal", inversedBy="dishes")
     * @ORM\JoinColumn(name="meal_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $meal;

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
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return $this
     */
    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return DishCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param DishCategory $category
     * @return Dish
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Meal
     */
    public function getMeal(): Meal
    {
        return $this->meal;
    }

    /**
     * @param Meal $meal
     * @return Dish
     */
    public function setMeal(Meal $meal): Dish
    {
        $this->meal = $meal;
        return $this;
    }
}
