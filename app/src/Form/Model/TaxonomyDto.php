<?php

namespace App\Form\Model;

use App\Entity\Taxonomy;

class TaxonomyDto {
    public $name;

    public static function createFromTaxonomy(Taxonomy  $taxonomy): self
    {
        $dto = new self();
        $dto->name = $taxonomy->getName();
        return $dto;
    }
}