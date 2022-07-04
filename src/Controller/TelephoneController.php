<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Telephone;
use App\Repository\TelephoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TelephoneController extends AbstractController
{
    #[Route('/user/{user}/telephone', methods: 'POST')]
    public function store(User $user, Request $request, TelephoneRepository $telephoneRepository): Response
    {
        $content = $request->toArray();

        $telephone = new Telephone();
        $telephone->setDdd($content['ddd']);
        $telephone->setNumber($content['number']);
        $telephone->setOwnerUser($user);
        $telephone->setCreatedAt(new \DateTimeImmutable());
        $telephone->setUpdatedAt(new \DateTimeImmutable());

        $telephoneRepository->add($telephone, true);

        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }
}
