<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\Taxonomy;
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     */
    public function postAction(
        EntityManagerInterface $em,
        Request $request,
        FileUploader $fileUploader
    ) {
        $productDto = new ProductDto();
        $form = $this->createForm(ProductFormType::class, $productDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()){
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()) {
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
     * @Rest\Post(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
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
        if($form->isValid()) {
            // Remove Taxonomy
            foreach ($originalTaxonomies as $originalTaxonomyDto) {
                if (!in_array($originalTaxonomyDto, $productDto->taxonomies)) {
                    $taxonomy = $taxonomyRepository->find($originalTaxonomyDto->id);
                    $product->removeTaxonomy($taxonomy);
                }
            }

            //add Taxonomy
            foreach ($productDto->taxonomies as $newTaxonomyDto) {
                if (!$originalTaxonomies->contains($newTaxonomyDto)) {
                    $taxonomy = $taxonomyRepository->find($newTaxonomyDto->id ?? 0);
                    if(!$taxonomy) {
                        $taxonomy = new Taxonomy();
                        $taxonomy->setName($newTaxonomyDto->name);
                        $em->persist($taxonomy);
                    }
                    $product->addTaxonomy($taxonomy);
                }
            }
            $product->setName($product->name);
            if ($productDto->base64Image) {
                $filename = $fileUploader->uploadBase64File($productDto->base64Image);
                $product->setImage($filename);
            }
            $em->persist($product);
            $em->flush();
            $em->refresh($product);
            return $product;
        }
        return $form;
    }
}