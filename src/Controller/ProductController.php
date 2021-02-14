<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="list_products", methods={"GET"})
     */
    public function showList(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll(), 200);
    }

    /**
     * @Route("/product/{id}", name="show_product", methods={"GET"})
     */
    public function showProduct(Product $product): Response
    {
        return $this->json($product, 200);
    }

}
