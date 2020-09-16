<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Pagination;
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
    public function List(UserRepository $repo, Request $request, Pagination $pagination): Response
    {
        $users = $pagination->findPaginatedList($repo, $request);


        return new Response(
            $this->serializer->serialize(
                $users,
                'json',
                SerializationContext::create()->setGroups(['Default', 'items' => ['list']])
            ),
            200,
            ['Content-Type' => 'application/hal+json']
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
