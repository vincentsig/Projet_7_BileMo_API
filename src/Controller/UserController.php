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
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;


class UserController extends AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @Route("/api/users", name="list_users", methods={"GET"})
     * @return Response
     * 
     * @SWG\Get(
     *     description="Get the list of users.",
     *   tags = {"User"},
     * )
     * 
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="page number"
     * ),
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="number items per page"
     * ),
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of users",
     *           @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=User::class, groups={"list"}))
     *          )
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * 
     * @SWG\Tag(name="User")
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
     * 
     * @SWG\Get(
     *      description="Get the details of one user.",
     *      tags = {"User"},
     * )
     * 
     * @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id of the phone",
     *      required=true,
     *      type="integer",
     * )
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of users",
     *           @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=User::class, groups={"list","details"}))
     *          )
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * 
     * @SWG\Tag(name="User")
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
