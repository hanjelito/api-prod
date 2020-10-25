<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/products")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(ProductRepository $productRepository)
    {
        return $productRepository->findAll();
    }

    /**
     * @Rest\Post(path="/products")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @param EntityManagerInterface $em
     * @return Product
     */
    public function postAction(
        EntityManagerInterface $em
    ) {
        $product = new Product();
        $product->setName('Istalling FOS Rest Bundles');
        $em->persist($product);
        $em->flush();
        return $product;
    }
}