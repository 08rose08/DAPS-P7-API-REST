<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\Pagination;
use OpenApi\Annotations as OA;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class ProductController extends AbstractController
{
    /**
     * Gets the product list
     * 
     * @Route("/products", name="list_products", methods={"GET"})
     * 
     * @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number of paginated products",
     *      @OA\Schema(type="integer")
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Return a JSON object of the product list",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Product::class, groups={"Default"}))
     *      ),
     * )
     * @OA\Response(
     *      response=401,
     *      description="JWT Token not found or expired or invalid",
     * )
     * @OA\Response(
     *      response=405,
     *      description="Method not allowed",
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="Product")
     * 
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param Pagination $pagination
     * @return JsonResponse
     */
    public function showList(ProductRepository $productRepository, SerializerInterface $serializer, Request $request, Pagination $pagination): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 3;        
        $totalCollection = count($productRepository->findAll());
        $products = $productRepository->findAllProducts($page, $limit);
        $route = 'list_products';

        $paginatedCollection = $pagination->paginate($page, $limit, $totalCollection, $products, $route);
        $json = $serializer->serialize($paginatedCollection, 'json', SerializationContext::create()->setGroups(array('Default')));

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Gets the product
     * 
     * @Route("/product/{id}", name="show_product", methods={"GET"})
     * 
     * @OA\Parameter(
     *      name="id",
     *      description="Products Id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Return a JSON object of the product",
     *      @OA\JsonContent(ref=@Model(type=Product::class, groups={"Default", "showProduct"})),
     * )
     * @OA\Response(
     *      response=401,
     *      description="JWT Token not found or expired or invalid",
     * )
     * @OA\Response(
     *      response=404,
     *      description="Resource not found",
     * )
     * @OA\Response(
     *      response=405,
     *      description="Method not allowed",
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="Product")
     */
    public function showProduct(Product $product, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(array('Default', 'showProduct')));
        return new JsonResponse($json, 200, [], true);
        //return $this->json($product, 200);
    }

}
