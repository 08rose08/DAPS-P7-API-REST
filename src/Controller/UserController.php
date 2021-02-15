<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="list_users", methods={"GET"})
     */
    public function showList(UserRepository $userRepository): Response
    {
        //ajouter un critère avec le getUser et présenter que les users liés au client connecté
        return $this->json($userRepository->findBy(["customer" => $this->getUser()]), 200, [], ['groups' => 'usersList']);
    }

    /**
     * @Route("/user/{id}", name="show_user", methods={"GET"})
     */
    public function showUser(User $user)
    {
        if($user->getCustomer() == $this->getUser()){
            return $this->json($user, 200, [], ['groups' => 'usersList']);

        }else{
            $data = ['status' => 403, 'message' => 'Forbidden'];
            return $this->json($data, 403);
        }
    }

    /**
     * @Route("/user", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        if($request->getContent() != null){
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');
            if($user->getName() != null && $user->getEmail() != null){
                $user->setCustomer($this->getUser());
                //Comment vérifier le $user ?
                $entityManager->persist($user);
                $entityManager->flush();
    
                $data = ['status' => 201, 'message' => 'User added'];
                return $this->json($data, 201);
            }
        }
        $data = ['status' => 400, 'message' => 'Bad request'];
        return $this->json($data, 400);
    }

    /**
     * @Route("/user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        if($user->getCustomer() == $this->getUser()){
            $entityManager->remove($user);
            $entityManager->flush();
            // quel code ??
            $data = ['status' => 200, 'message' => 'User deleted'];
            return $this->json($data, 200);

        }else{
            $data = ['status' => 403, 'message' => 'Forbidden'];
            return $this->json($data, 403);
        }
    }
}
