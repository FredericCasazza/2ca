<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DishCategoryRepository")
 */
class DishCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=35, nullable=false)
     */
    private $label;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $position = 1;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $dishLimit = 0;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $enable = true;

    /**
     * @ORM\Column(type="json")
     */
    private $dishes = [];

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
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getDishLimit(): int
    {
        return $this->dishLimit;
    }

    /**
     * @param int $dishLimit
     * @return DishCategory
     */
    public function setDishLimit(int $dishLimit): DishCategory
    {
        $this->dishLimit = $dishLimit;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return array
     */
    public function getDishes(): array
    {
        return $this->dishes;
    }

    /**
     * @param string $dish
     * @return $this
     */
    public function addDish(string $dish): self
    {
        $key = md5($dish);
        if(!in_array($dish, $this->dishes))
        {
            $this->dishes[$key] = $dish;
        }
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeDish(string $key): self
    {
        if(array_key_exists($key, $this->dishes))
        {
            unset($this->dishes[$key]);
        }
        return $this;
    }
}
