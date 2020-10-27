<?php

namespace App\Form\Model;

use App\Entity\Product;

class ProductDto {
    public $name;
    public $cost;
    public $base64Image;
    public $taxonomies;

    public function __Constructor()
    {
        $this->taxonomies = [];
    }

    public static function createFromProduct(Product $product): self
    {
        $dto = new self();
        $dto->name = $product->getName();
        $dto->cost = $product->getCost();
        return $dto;
    }
}