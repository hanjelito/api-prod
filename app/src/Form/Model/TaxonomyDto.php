<?php

namespace App\Form\Model;

use App\Entity\Taxonomy;

class TaxonomyDto {

    public $id;
    public $name;

    public static function createFromTaxonomy(Taxonomy  $taxonomy): self
    {
        $dto        = new self();
        $dto->id    = $taxonomy->getId();
        $dto->name  = $taxonomy->getName();
        return $dto;
    }
}