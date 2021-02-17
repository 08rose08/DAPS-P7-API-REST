<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="list_products", methods={"GET"})
     */
    public function showList(ProductRepository $productRepository, SerializerInterface $serializer, Request $request): Response
    {
        //$json = $serializer->serialize($productRepository->findAll(), 'json', SerializationContext::create()->setGroups(array('Default')));
        //return new JsonResponse($json, 200, [], true);
        //return $this->json($productRepository->findAll(), 200);
        $page = $request->query->get('page', 1);
        $limit = 3;

        $products = $productRepository->findAllProducts($page, $limit);
        $json = $serializer->serialize($products, 'json', SerializationContext::create()->setGroups(array('Default')));

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/product/{id}", name="show_product", methods={"GET"})
     */
    public function showProduct(Product $product, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(array('Default', 'showProduct')));
        return new JsonResponse($json, 200, [], true);
        //return $this->json($product, 200);
    }

}
