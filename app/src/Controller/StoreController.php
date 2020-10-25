<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
        $title = $request->get('title', 'No existe');
        $products = $productRepository->findAll();
        $productsAsArray = [];
        foreach ($products as $product)
        {
            $productsAsArray[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'image' => $product->getImage()
            ];
        }
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data'=> $productsAsArray
        ]);
        return $response;
    }
    /**
     * @Route("/product/create", name="create_product")
     */
    public function createProduct(Request $request, EntityManagerInterface $em)
    {
        $product = new Product();
        $response = new JsonResponse();
        $name = $request->get('name', null);
        if(empty($name))
        {
            $response->setData([
                'success' => false,
                'error' => 'Title cannot be empty',
                'data'=> null
            ]);
            return $response;
        }
        $product->setName($name);
        $em->persist($product);
        $em->flush();
        $response->setData([
            'success' => true,
            'data'=> [
                [
                    'id'=> $product->getId(),
                    'name'=> $product->getName()
                ]
            ]
        ]);
        return $response;
    }
}