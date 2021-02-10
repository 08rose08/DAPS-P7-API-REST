<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="list_products")
     */
    public function showList(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll(), 200);
    }

    /**
     * @Route("/product/{id}", name="show_product")
     */
    public function showProduct(Product $product): Response
    {
        return $this->json($product, 200);
    }

}
