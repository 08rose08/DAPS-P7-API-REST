<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        return $this->json($userRepository->findAll(), 200, [], ['groups' => 'usersList']);
    }

    /**
     * @Route("/user/{id}", name="show_user", methods={"GET"})
     */
    public function showUser(User $user)
    {
        //idem
        return $this->json($user, 200, [], ['groups' => 'usersList']);
    }
}
