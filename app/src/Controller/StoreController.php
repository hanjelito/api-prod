<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class StoreController extends  AbstractController
{
    /**
     * @Route("/store/list", name="store_list")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function list(Request $request, LoggerInterface  $logger)
    {
        $title = $request->get('title', 'No existe');
        $logger->info('List Action Cancelled');
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data'=> [
                [
                    'id'=> 1,
                    'title'=>'xmen'
                ],
                [
                    'id'=> 2,
                    'title'=> 'George de la selva'
                ],
                [
                    'id'=> 3,
                    'title'=> $title
                ]
            ]
        ]);
        return $response;
    }
}