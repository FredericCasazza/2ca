<?php

namespace App\Entity;

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
    private $limit = 1;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $enable = true;

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
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return DishCategory
     */
    public function setLimit(int $limit): DishCategory
    {
        $this->limit = $limit;
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
}
