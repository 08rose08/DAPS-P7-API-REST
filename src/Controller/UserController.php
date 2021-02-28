<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Pagination;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\Items;
use App\Repository\UserRepository;
use OpenApi\Annotations\RequestBody;
//use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * Gets the user list of the customer
     * 
     * @Route("/users", name="list_users", methods={"GET"})
     * 
     * @OA\Parameter(
     *      name="page",
     *      description="The page number of paginated users",
     *      in="query",
     *      @OA\Schema(type="integer"),
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Return a JSON object of the user list",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=User::class, groups={"Default", "usersList"}))
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
     * @OA\Tag(name="User")
     * 
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param Pagination $pagination
     * @return JsonResponse
     */
    public function showList(UserRepository $userRepository, SerializerInterface $serializer, Request $request, Pagination $pagination): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 3;
        $totalCollection = count($userRepository->findBy(["customer" => $this->getUser()]));
        $users = $userRepository->findUsers($page, $limit, $this->getUser());
        $route = 'list_users';

        $paginatedCollection = $pagination->paginate($page, $limit, $totalCollection, $users, $route);
        $json = $serializer->serialize($paginatedCollection, 'json', SerializationContext::create()->setGroups(array('Default', 'usersList')));
        
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Gets the user
     * 
     * @Route("/user/{id}", name="show_user", methods={"GET"})
     * 
     * @OA\Parameter(
     *      name="id",
     *      description="User's Id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Return a JSON object of the product",
     *      @OA\JsonContent(ref=@Model(type=User::class, groups={"Default", "usersList"})),
     * )
     * @OA\Response(
     *      response=401,
     *      description="JWT Token not found or expired or invalid",
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied",
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
     * @OA\Tag(name="User")
     * 
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showUser(User $user, SerializerInterface $serializer)
    {
        if($user->getCustomer() == $this->getUser()){
            //return $this->json($user, 200, [], ['groups' => 'usersList']);
            $json = $serializer->serialize(
                $user,
                'json',
                SerializationContext::create()->setGroups(array('Default', 'usersList'))
            );
            return new JsonResponse($json, 200, [], true);
        }else{
            throw new AccessDeniedHttpException();

            //$data = ['status' => 403, 'message' => 'Forbidden'];
            //return $this->json($data, 403);
        }
    }

    /**
     * Creates a new user
     * 
     * @Route("/user", name="create_user", methods={"POST"})
     * 
     * @OA\RequestBody(
     *      description="Create user object",
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=User::class, groups={"createUser"})),     
     * )
     * 
     * @OA\Response(
     *      response=204,
     *      description="Create an user",
     *      @OA\JsonContent(ref=@Model(type=User::class, groups={"Default", "usersList"})),
     * )
     * @OA\Response(
     *      response=400,
     *      description="Bad request",
     * )
     * @OA\Response(
     *      response=401,
     *      description="JWT Token not found or expired or invalid",
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied",
     * )
     * @OA\Response(
     *      response=404,
     *      description="Resource not found",
     * )
     * @OA\Response(
     *      response=405,
     *      description="Method not allowed",
     * )
     * @OA\Response(
     *      response=500,
     *      description="Could not decode JSON, syntax error - malformed JSON.",
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="User")
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, UserRepository $userRepository)
    {
        if($request->getContent() != null){
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');
            //if($user->getName() != null && $user->getEmail() != null){
                $user->setCustomer($this->getUser());
                //Comment vérifier le $user ?
                $errors = $validator->validate($user);
                if (count($errors)) {
                    return $this->json($errors, 400);
                }

                $entityManager->persist($user);
                $entityManager->flush();
    
                //$data = ['status' => 201, 'message' => 'User added'];
                //return $this->json($data, 201);
                $user = $userRepository->findBy(["email" => $user->getEmail()]);
                $json = $serializer->serialize(
                    $user,
                    'json',
                    SerializationContext::create()->setGroups(array('Default', 'usersList'))
                );
                return new JsonResponse($json, 201, [], true);

            //}
        }
        //$data = ['status' => 400, 'message' => 'Bad request'];
        //return $this->json($data, 400);
        throw new BadRequestHttpException('Data are missing');
    }

    /**
     * Deletes the user
     * 
     * @Route("/user/{id}", name="delete_user", methods={"DELETE"})
     * 
     * @OA\Parameter(
     *      name="id",
     *      description="Users Id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * )
     * 
     * @OA\Response(
     *      response=204,
     *      description="Delete the user",
     * )
     * @OA\Response(
     *      response=401,
     *      description="JWT Token not found or expired or invalid",
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied",
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
     * @OA\Tag(name="User")
     * 
     * @param User $user
     * @param EntityManagerInterface $entityManager
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        if($user->getCustomer() == $this->getUser()){
            $entityManager->remove($user);
            $entityManager->flush();
            // quel code ?? -> 204 avec body vide
            //$data = ['status' => 200, 'message' => 'User deleted'];
            return $this->json(null, 204);

        }else{
            //$data = ['status' => 403, 'message' => 'Forbidden'];
            //return $this->json($data, 403);
            throw new AccessDeniedHttpException();
        }
    }
}
