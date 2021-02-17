<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Serializer\SerializerInterface;
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
     * @Route("/users", name="list_users", methods={"GET"})
     */
    public function showList(UserRepository $userRepository, SerializerInterface $serializer, Request $request): Response
    {
        /*$json = $serializer->serialize(
            $userRepository->findBy(["customer" => $this->getUser()]),
            'json',
            SerializationContext::create()->setGroups(array('Default', 'usersList'))
        );
        return new JsonResponse($json, 200, [], true);*/
        //return $this->json($userRepository->findBy(["customer" => $this->getUser()]), 200, [], ['groups' => 'usersList']);
        $page = $request->query->get('page', 1);
        $limit = 3;

        $users = $userRepository->findUsers($page, $limit, $this->getUser());
        $json = $serializer->serialize($users, 'json', SerializationContext::create()->setGroups(array('Default', 'usersList')));

        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/user/{id}", name="show_user", methods={"GET"})
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
     * @Route("/user", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
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
    
                $data = ['status' => 201, 'message' => 'User added'];
                return $this->json($data, 201);
            //}
        }
        //$data = ['status' => 400, 'message' => 'Bad request'];
        //return $this->json($data, 400);
        throw new BadRequestHttpException('Data are missing');
    }

    /**
     * @Route("/user/{id}", name="delete_user", methods={"DELETE"})
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
