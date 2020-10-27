<?php

namespace App\Form\Model;

use App\Entity\Product;

class ProductDto {
    public $name;
    public $description;
    public $base64Image;
    public $taxonomies;
    public $cost;
    public $costFinal;

    public function __Constructor()
    {
        $this->taxonomies = [];
    }

    public static function createFromProduct(Product $product): self
    {
        $dto = new self();
        $dto->name          = $product->getName();
        $dto->description   = $product->getDescription();
        $dto->cost          = $product->getCost();
        return $dto;
    }
}