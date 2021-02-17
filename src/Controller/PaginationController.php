<?php

namespace App\Controller;

use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class PaginationController extends AbstractController
{
    
    public function paginate($page, $limit, $totalCollection, $products, $route)
    {
        $totalPages = intval($totalCollection/$limit);
        if(fmod($totalCollection, $limit) != 0){$totalPages++;}

        if($page > $totalPages){throw new BadRequestHttpException('Invalid page number. Number max = '.$totalPages);}

        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($products),
            $route, // route
            array(), // route parameters
            $page,       // page number
            $limit,      // limit
            $totalPages,       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false,   // generate relative URIs, optional, defaults to `false`
            $totalCollection       // total collection size, optional, defaults to `null`
        );
        return $paginatedCollection;
    }

}
