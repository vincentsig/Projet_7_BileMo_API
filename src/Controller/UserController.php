<?php

namespace App\Controller;

use App\Entity\User;
use Swagger\Annotations as SWG;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use AppBundle\Exception\ResourceValidationException;

class UserController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/users", name="api_user_list", methods={"GET"})
     * @return Response
     * @param UserRepository $repo
     * @param Request $request
     *
     * @SWG\Get(
     *     description="Get the paginated list of users corresponding to the company.",
     *     tags = {"User"},
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
     *     description="OK: Returns the paginated list of users",
     *              @SWG\Schema(
     *                  type="array",
     *                  @SWG\Items(ref=@Model(type=User::class, groups={"list"}))
     *              )
     * ),
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request: Returned when the page and/or the limit parameter for the pagination are not numeric",
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="Unauthorized: Returned when the JWT Token is not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not Found: Returned when the route is invalid",
     * ),
     * @SWG\Response(
     *     response=405,
     *     description="Method not allowed: Returned when the HTTP method used is not valid on this endpoint",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Internal Server error: Returned when there is an Internal Server error",
     * )
     *
     * @SWG\Tag(name="User")
     * @Security(name="Bearer")
     */
    public function list(UserRepository $repo, Request $request): Response
    {
        //Get the cache request pagination or if it doesnt exist paginate the user list and save it in cache
        $users =  $repo->usersPagination($request, $this->getUser()->getId());

        return new Response(
            $this->serializer->serialize($users, 'json', SerializationContext::create()->setGroups(['Default', 'items' => ['list']])),
            200,
            ['Content-Type' => 'application/hal+json']
        );
    }

    /**
     * @param User $user
     * @return Response
     *
     * @Route("api/users/{id}", name="api_user_details", methods={"GET"})
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
     *     description="OK: Returns the details one user",
     *           @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=User::class, groups={"details"}))
     *          )
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="Unauthorized: Returned when the JWT Token is not found or expired",
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Acces Denied: Returned when you don't have acces to a ressource",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not Found: Returned when the ressource is not founds or the route is invalid",
     * ),
     * @SWG\Response(
     *     response=405,
     *     description="Method not allowed: Returned when the HTTP method used is not valid on this endpoint",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Internal Server error: Returned when there is an Internal Server error",
     * )
     *
     * @SWG\Tag(name="User")
     * @Security(name="Bearer")
     */
    public function details(User $user): Response
    {
        $this->denyAccessUnlessGranted('GET_USER', $user);

        return new Response(
            $this->serializer->serialize($user, 'json', SerializationContext::create()->setGroups(['list', 'details'])),
            200,
            ['Content-Type' => 'application/hal+json']
        );
    }

    /**
     * @return Response
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @Route("api/users", name="api_add_user", methods={"POST"})
     *
     *
     * @SWG\Post(
     *      description="Create a new user",
     *      tags = {"User"},
     * ),
     *
     * @SWG\Parameter(
     *          name="Body",
     *          required= true,
     *          in="body",
     *          type="string",
     *          description="All property user to add",
     * @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=User::class, groups={"details"}))
     *            )
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="OK: The user has been created",
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="Unauthorized: Returned when the JWT Token is not found or expired",
     * ),
     *@SWG\Response(
     *     response=405,
     *     description="Method not allowed: Returned when the HTTP method used is not valid on this endpoint",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Internal Server error: Returned when there is an Internal Server error",
     * )
     *
     * @SWG\Tag(name="User")
     * @Security(name="Bearer")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if (count($errors)) {
            $violations = [];
            foreach ($errors as $violation) {
                $violations[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            $data = $this->serializer->serialize(
                [
                    $violations,
                ],
                'json'
            );

            throw new ResourceValidationException($data, null, 400);
        }

        $user->setCompany($this->getUser());
        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'status' => 201,
            'message' => 'The user has been created'
        ];

        return new Response($this->serializer->serialize($data, 'json'), 201, ['Content-Type' => 'application/hal+json']);
    }

    /**
     * @return Response
     * @param User $user
     * @param EntityManagerInterface $entityManager
     *
     *
     * @Route("api/users/{id}", name="api_remove_user", methods="DELETE")
     *
     *
     * @SWG\Delete(
     *      description="Remove a user belonging to your company",
     *      tags = {"User"},
     * ),
     *
     * @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id of the user",
     *      required=true,
     *      type="integer",
     * ),
     *
     * @SWG\Response(
     *     response=204,
     *     description="No Content",
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="Unauthorized: Returned when the JWT Token is not found or expired",
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Acces Denied: Returned when you don't have acces to a ressource",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not Found: Returned when the ressource is not founds or the route is invalid",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Internal Server error: Returned when there is an Internal Server error",
     * )
     *
     * @SWG\Tag(name="User")
     * @Security(name="Bearer")
     */
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('DELETE_USER', $user);

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response(null, 204, ['Content-Type' => 'application/json']);
    }
}
