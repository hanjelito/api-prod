<?php

namespace App\Service;

use App\Entity\Product;
use App\Form\Model\ProductDto;
use App\Form\Model\TaxonomyDto;
use App\Form\Type\ProductFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class ProductFormProcessor
{
    private $productManager;
    private $taxonomyManager;
    private $fileUploader;
    private $formFactory;

    public function __construct(
        ProductManager          $productManager,
        TaxonomyManager         $taxonomyManager,
        FileUploader            $fileUploader,
        FormFactoryInterface    $formFactory
    ){
        $this->productManager   = $productManager;
        $this->taxonomyManager  = $taxonomyManager;
        $this->fileUploader     = $fileUploader;
        $this->formFactory      = $formFactory;
    }
    public function __invoke(Product $product, Request $request)
    {
        $productDto         = ProductDto::createFromProduct($product);
        $originalTaxonomies = new ArrayCollection();

        foreach ($product->getTaxonomies() as $taxonomy) {

            $taxonomyDto                = TaxonomyDto::createFromTaxonomy($taxonomy);
            $productDto->taxonomies[]   = $taxonomyDto;
            $originalTaxonomies->add($taxonomyDto);
        }

        $form = $this->formFactory->create(ProductFormType::class, $productDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return [null, 'For is not submitted'];
        }
        if($form->isValid()) {
            // Remove Taxonomy
            foreach ($originalTaxonomies as $originalTaxonomyDto) {
                if (!in_array($originalTaxonomyDto, $productDto->taxonomies)) {
                    $taxonomy = $this->taxonomyManager->find($originalTaxonomyDto->id);
                    $product->removeTaxonomy($taxonomy);
                }
            }

            //add Taxonomy
            foreach ($productDto->taxonomies as $newTaxonomyDto) {
                if (!$originalTaxonomies->contains($newTaxonomyDto)) {
                    $taxonomy = $this->taxonomyManager->find($newTaxonomyDto->id ?? 0);
                    if(!$taxonomy) {
                        $taxonomy = $this->taxonomyManager->create();
                        $taxonomy->setName($newTaxonomyDto->name);
                        $this->taxonomyManager->persist($taxonomy);
                    }
                    $product->addTaxonomy($taxonomy);
                }
            }
            $product->setName($productDto->name);
            if ($productDto->base64Image) {
                $filename = $this->fileUploader->uploadBase64File($productDto->base64Image);
                $product->setImage($filename);
            }

            $this->productManager->save($product);
            $this->productManager->reload($product);
            return [$product, null];
        }
        return [null, $form];
    }
}