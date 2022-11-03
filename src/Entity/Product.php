<?php

namespace App\Entity;

use App\Exceptions\ProductIsNotEnoughException;
use App\Traits\Identificable;
use App\Traits\Nameable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProductRepository;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="kf_product")
 */
class Product
{
    use Identificable, Nameable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true, options={"default"="NULL"})
     * @Assert\Length(max="500")
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     * @Assert\Regex(pattern="/^\d+(\.\d{1,})?$/", message="Invalid quantity")
     */
    protected $stock;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $image;

    /**
     * @var int|null
     *
     * @ORM\Column(name="leftover", type="integer", nullable=true)
     */
    protected $leftover;

    /**
     * @var string
     *
     * @ORM\Column(name="measurement_unit", type="string", length=3, nullable=false)
     * @Assert\Length(max="3")
     */
    protected $measurementUnit;

    /**
     * @ORM\OneToMany(targetEntity=ProductRecipe::class, mappedBy="recipe", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $recipes;

    public function __construct()
    {
        $this->stock = 0;
        $this->recipes = new ArrayCollection();
    }


    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Product
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     * @return Product
     */
    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return Product
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLeftover(): ?int
    {
        return $this->leftover;
    }

    /**
     * @param int|null $leftover
     * @return Product
     */
    public function setLeftover(?int $leftover): self
    {
        $this->leftover = $leftover;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMeasurementUnit(): ?string
    {
        return $this->measurementUnit;
    }

    /**
     * @param string $measurementUnit
     * @return Product
     */
    public function setMeasurementUnit(string $measurementUnit): self
    {
        $this->measurementUnit = $measurementUnit;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    /**
     * @param Recipe $recipe
     * @return Product
     */
    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
        }

        return $this;
    }

    /**
     * @param Recipe $recipe
     * @return Product
     */
    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
        }

        return $this;
    }

    /**
     * @param float $quantity
     * @return bool
     */
    public function isEnough(float $quantity): bool
    {
        return $this->getLeftover() + $this->getStock() - $quantity > 0;
    }
}