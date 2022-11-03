<?php

namespace App\Entity;

use App\Traits\Identificable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRecipeRepository;

/**
 * @ORM\Entity(repositoryClass=ProductRecipeRepository::class)
 * @ORM\Table(name="kf_product_recipe")
 */
class ProductRecipe
{
    use Identificable;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class)
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id", nullable=false)
     */
    protected $recipe;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    protected $quantity;

    /**
     * @return Recipe|null
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     * @return ProductRecipe
     */
    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return ProductRecipe
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return ProductRecipe
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}