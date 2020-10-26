<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager
{
    private $em;
    private $productRepository;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $this->em                   = $em;
        $this->productRepository    = $productRepository;
    }

     public function find(int $id): ?Product
     {
         return $this->productRepository->find($id);
     }

     public function getRepository(): ProductRepository
     {
         return $this->productRepository;
     }

     public function create(): Product
     {
         return new Product();
     }

     public function save(Product $product)
     {
         $this->em->persist($product);
         $this->em->flush();

         return $product;
     }

     public function reload(Product $product)
     {
         $this->em->refresh($product);

         return $product;
     }

     public function delete(Product $product)
     {
         $this->em->remove($product);
         $this->em->flush();
     }

}