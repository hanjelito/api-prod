<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class StoreController extends  AbstractController
{
    /**
     * @Route("/products", name="product_list")
     */
    public function list(Request $request, ProductRepository $productRepository)
    {
        $response = new JsonResponse();
        return $response;
    }
    /**
     * @Route("/product/create", name="create_product")
     */
    public function createProduct(Request $request, EntityManagerInterface $em)
    {
        $response = new JsonResponse();
        return $response;
    }
}