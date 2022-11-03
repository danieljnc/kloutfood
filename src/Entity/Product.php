<?php

namespace App\Entity;

use App\Traits\Identificable;
use App\Traits\Nameable;
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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $image;

    /**
     * @var int
     *
     * @ORM\Column(name="leftover", type="integer", nullable=false)
     */
    protected $leftover;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}