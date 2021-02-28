<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    /*public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if(isset($values->name, $values->password, $values->email)) {
            $customer = new Customer();
            $customer->setName($values->name);
            $customer->setEmail($values->email);
            $customer->setPassword($passwordEncoder->encodePassword($customer, $values->password));
            $customer->setRoles($customer->getRoles());
            $errors = $validator->validate($customer);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($customer);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Le client a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés name, email et password'
        ];
        return new JsonResponse($data, 500);
    }*/

    /**
     * @Route("/login", name="login", methods={"POST"})
     * @OA\Tag(name="Customer")
     */
    public function login(Request $request)
    {
        $customer = $this->getUser();
        return $this->json([
            'username' => $customer->getName(),
            'roles' => $customer->getRoles()
        ]);
    }
}
