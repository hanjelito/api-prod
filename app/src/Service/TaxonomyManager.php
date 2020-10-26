<?php

namespace App\Service;

use App\Entity\Taxonomy;
use App\Repository\TaxonomyRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaxonomyManager
{
    private $em;
    private $taxonomyRepository;

    public function __construct(EntityManagerInterface $em, TaxonomyRepository  $taxonomyRepository)
    {
        $this->em                   = $em;
        $this->taxonomyRepository   = $taxonomyRepository;
    }

    public function find(int $id): ?Taxonomy
    {
        return $this->taxonomyRepository->find($id);
    }

    public function getRepository(): TaxonomyRepository
    {
        return $this->taxonomyRepository;
    }

    public function create(): Taxonomy
    {
        return new Taxonomy();
    }

    public function persist(Taxonomy $taxonomy): Taxonomy
    {
        $this->em->persist($taxonomy);
        return $taxonomy;
    }

    public function save(Taxonomy $taxonomy)
    {
        $this->em->persist($taxonomy);
        $this->em->flush();

        return $taxonomy;
    }

    public function reload(Taxonomy $taxonomy)
    {
        $this->em->refresh($taxonomy);

        return $taxonomy;
    }

}