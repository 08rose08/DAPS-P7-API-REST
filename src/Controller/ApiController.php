<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api_index", methods={"GET"})
     */
    public function index()
    {
        $data = ['id' => 1, 'name' => 'Charlie'];
        return $this->json($data, 200);
    }
}
