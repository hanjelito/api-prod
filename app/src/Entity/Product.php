<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Taxonomy::class, inversedBy="products")
     */
    private $taxonomies;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $cost;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cost_final;

    public function __construct()
    {
        $this->taxonomies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Taxonomy[]
     */
    public function getTaxonomies(): Collection
    {
        return $this->taxonomies;
    }

    public function addTaxonomy(Taxonomy $taxonomy): self
    {
        if (!$this->taxonomies->contains($taxonomy)) {
            $this->taxonomies[] = $taxonomy;
        }

        return $this;
    }

    public function removeTaxonomy(Taxonomy $taxonomy): self
    {
        $this->taxonomies->removeElement($taxonomy);

        return $this;
    }



    public function getCostFinal(): ?float
    {
        return $this->cost_final;
    }

    public function setCostFinal(?float $cost_final): self
    {
        $this->cost_final = $cost_final;

        return $this;
    }

}
