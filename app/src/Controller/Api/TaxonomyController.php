<?php

namespace App\Controller\Api;


use App\Service\TaxonomyManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class TaxonomyController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/taxonomies")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        TaxonomyManager $categoryManager
    ){
        return $categoryManager->getRepository()->findAll();
    }
}