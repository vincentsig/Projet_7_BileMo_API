<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @Route("/api/users", name="list_user", methods={"GET"})
     */
    public function List(UserRepository $repo, Request $request): Response
    {

        return new Response(
            $this->serializer->serialize(
                $repo->findAll(),
                'json',
                SerializationContext::create()->setGroups(['list'])
            ),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("api/users/{id}", name="details_user", methods={"GET"})
     */
    public function Details(User $user, UserRepository $repo, Request $request): Response
    {
        return new Response(
            $this->serializer->serialize(
                $repo->find($user->getId()),
                'json',
                SerializationContext::create()->setGroups(['list', 'details'])
            ),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
