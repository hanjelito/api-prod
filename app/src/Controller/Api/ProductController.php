<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Form\Model\ProductDto;
use App\Form\Model\TaxonomyDto;
use App\Form\Type\ProductFormType;
use App\Repository\ProductRepository;
use App\Repository\TaxonomyRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use http\Env\Response;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/products")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @param ProductRepository $productRepository
     * @return Product[]
     */
    public function getAction(ProductRepository $productRepository)
    {
        return $productRepository->findAll();
    }

    /**
     * @Rest\Post(path="/products")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function postAction(
        EntityManagerInterface $em,
        Request $request,
        FileUploader $fileUploader
    ) {
        $productDto = new ProductDto();
        $form = $this->createForm(ProductFormType::class, $productDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = new Product();
            $product->setName($productDto->name);
            if($productDto->base64Image) {
                $filename = $fileUploader->uploadBase64file($productDto->base64Image);
                $product->setImage($filename);
            }
            $em->persist($product);
            $em->flush();
            return $product;
        }
        return $form;
    }

    /**
     * @Rest\Post(path="/products/{id}", requeriments={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @param int $id
     * @param EntityManagerInterface $em
     * @param ProductRepository $productRepository
     * @param TaxonomyRepository $taxonomyRepository
     * @param Request $request
     * @param FileUploader $fileUploader
     */
    public function editAction(
        int $id,
        EntityManagerInterface $em,
        ProductRepository $productRepository,
        TaxonomyRepository $taxonomyRepository,
        Request $request,
        FileUploader $fileUploader
    ){
        $product = $productRepository->find($id);
        if(!$product) {
            throw $this->createAccessDeniedException('Product not found');
        }
        $productDto = ProductDto::createFromProduct($product);

        $originalTaxonomies = new ArrayCollection();
        foreach ($product->getTaxonomies() as $taxonomy) {
            $taxonomyDto = TaxonomyDto::createFromTaxonomy($taxonomy);
            $productDto->taxonomies[] = $taxonomyDto;
            $originalTaxonomies->add($taxonomyDto);
        }

        $form = $this->createForm(ProductFormType::class, $productDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
    }
}