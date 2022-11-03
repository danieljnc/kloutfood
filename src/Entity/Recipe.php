<?php

namespace App\Entity;

use App\Exceptions\ProductIsNotEnoughException;
use App\Traits\Identificable;
use App\Traits\Nameable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @ORM\Table(name="kf_recipe")
 */
class Recipe
{
    use Identificable, Nameable;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true, options={"default"="NULL"})
     * @Assert\Length(max="500")
     */
    protected $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $image;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     * @Assert\Regex(pattern="/^\d+(\.\d{1,})?$/", message="Invalid price")
     */
    protected $price;

    /**
     * @ORM\OneToMany(targetEntity=ProductRecipe::class, mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return Recipe
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return Recipe
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Recipe
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
     * @param float   $quantity
     * @return Recipe
     * @throws ProductIsNotEnoughException
     */
    public function addProduct(Product $product, float $quantity): self
    {
        if (!$product->isEnough($quantity)) {
            throw new ProductIsNotEnoughException();
        }

        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return Recipe
     */
    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }
}