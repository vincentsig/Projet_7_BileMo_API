<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Pagination;
use Swagger\Annotations as SWG;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * 
     * @SWG\Get(
     *     description="Get the paginated list of users.",
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
     * @Security(name="Bearer")
     */
    public function list(UserRepository $repo, Request $request, Pagination $pagination): Response
    {

        $company = $this->getUser()->getId();

        $users = $pagination->findPaginatedList($repo, $request, $company);

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
     * @Route("api/users/{id}", name="api_user_details", methods={"GET"})
     * @param $id
     * @return Response
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
     *     description="Returns the details of users",
     * @SWG\Schema(
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
     * @Security(name="Bearer")
     */
    public function details(User $user, UserRepository $repo, Request $request): Response
    {
        return new Response(
            $this->serializer->serialize(
                $repo->find($user->getId()),
                'json',
                SerializationContext::create()->setGroups(['list', 'details'])
            ),
            200,
            ['Content-Type' => 'application/hal+json']
        );
    }

    /**
     * @Route("api/users", name="api_add_user", methods={"POST"})
     * @return Response
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
     *          @SWG\Schema(
     *              type="array",
     *              @Model(type=User::class, groups={"details"}))
     * ),
     *    
     * @SWG\Response(
     *     response=200,
     *     description="User created",
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
     * @Security(name="Bearer")
     */
    public function create(CompanyRepository $repo, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
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
                    'status'            => 400,
                    'message'           => 'Bad request',
                    'invalid field(s):' => $violations,
                ],
                'json'
            );

            return new Response($data, 400, [
                'Content-Type' => 'application/json'
            ]);
        }

        $user->setCompany($repo->find($this->getUser()->getId()));
        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'status' => 201,
            'message' => 'The user has been created'
        ];

        return new Response($this->serializer->serialize($data, 'json'), 201, ['Content-Type' => 'application/hal+json']);
    }

    /**
     * @Route("api/users/{id}", name="api_remove_user", methods="DELETE")
     * @return Response
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
     *     response=200,
     *     description="User Removed",
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
     * @Security(name="Bearer")
     */
    public function delete(User $user, EntityManagerInterface $entityManager, UserRepository $repo): Response
    {

        $userValid = $repo->FindUserByCompany(($this->getUser()->getId()), $user->getId());

        if (!$userValid) {

            $statusCode = 401;
            $data = [
                'status' => $statusCode,
                'message' => 'Invalid user id'
            ];
        } else {
            $statusCode = 200;
            $data = [
                'status' => $statusCode,
                'message' => 'The user has been removed'
            ];

            $entityManager->remove($user);
            $entityManager->flush();
        }







        return new Response($this->serializer->serialize($data, 'json'), 200, ['Content-Type' => 'application/json']);
    }
}
