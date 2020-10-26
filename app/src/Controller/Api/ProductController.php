<?php

namespace App\Controller\Api;

use App\Service\ProductFormProcessor;
use App\Service\ProductManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/products")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        ProductManager $productManager
    ){
        return $productManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/products")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        ProductManager          $productManager,
        ProductFormProcessor    $productFormProcessor,
        Request                 $request
    ) {
        $product = $productManager->create();
        [$product, $error] = ($productFormProcessor)($product, $request);
        $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data       = $product ?? $error;
        return View::create($data, $statusCode);

    }

    /**
     * @Rest\Post(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */

    public function editAction(
        int $id,
        ProductFormProcessor    $productFormProcessor,
        ProductManager          $productManager,
        Request                 $request
    ){
        $product = $productManager->find($id);
        if(!$product) {
            return View::create('Product not found', Response::HTTP_BAD_REQUEST);
        }
        [$product, $error] = ($productFormProcessor)($product, $request);

        $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data       = $product ?? $error;
        return View::create($data, $statusCode);

    }

    /**
     * @Rest\Delete(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */

    public function deleteAction(
        int $id,
        ProductManager          $productManager
    ){
        $product = $productManager->find($id);
        if(!$product) {
            return View::create('Product not found', Response::HTTP_BAD_REQUEST);
        }
        $productManager->delete($product);
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}