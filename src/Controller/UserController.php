<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserController extends AbstractController
{
    #[Route('/user', methods: 'GET')]
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $name = $request->query->get('name');
        $email = $request->query->get('email');

        $criteria = [];

        if (!empty($name)) {
            $criteria['name'] = $name;
        }

        if (!empty($email)) {
            $criteria['email'] = $email;
        }

        $users = $userRepository->findBy($criteria);

        return $this->json($users, Response::HTTP_OK, [], [
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object) {
                return $object->getName();
            }
        ]);
    }

    #[Route('/user/{user}', methods: 'GET')]
    public function show(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK, [], [
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object) {
                return $object->getName();
            }
        ]);
    }

    #[Route('/user', methods: 'POST')]
    public function store(Request $request, UserRepository $userRepository): Response
    {
        $content = $request->toArray();
        
        $user = new User();
        $user->setName($content['name']);
        $user->setEmail($content['email']);

        $birthDate = new DateTime($content['birth_date']);
        $user->setBirthDate($birthDate);

        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $userRepository->add($user, true);

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);
 
        return $response;
    }

    #[Route('/user/{user}', methods: 'PUT')]
    public function update(User $user, Request $request, UserRepository $userRepository): Response
    {
        $content = $request->toArray();
        
        $user->setName($content['name']);
        $user->setEmail($content['email']);

        $birthDate = new DateTime($content['birth_date']);
        $user->setBirthDate($birthDate);

        $user->setUpdatedAt(new \DateTimeImmutable());

        $userRepository->add($user, true);

        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }
}