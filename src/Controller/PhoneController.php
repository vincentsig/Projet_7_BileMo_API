<?php

namespace App\Controller;

use App\Entity\Phone;
use Swagger\Annotations as SWG;
use App\Repository\PhoneRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return Response
     * @param Request $request
     * @param PhoneRepository $repo
     *
     * @Route ("api/phones", name = "list_phones", methods={"GET"})
     *
     * @SWG\Get(
     *      description="Endpoint for the list of all the phones",
     *      produces={"application/hal+json"},
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
     *     description="Returns list of all phones",
     * @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=Phone::class, groups={"list"}))
     *          )
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
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
     */
    public function list(Request $request, PhoneRepository $repo): Response
    {
        //Get the cache request pagination or if it doesnt exist paginate the phone list and save it in cache
        $phones = $repo->phonePagination($request);

        $data = $this->serializer->serialize($phones, 'json', SerializationContext::create()->setGroups(['Default', 'items' => ['list']]));

        return new Response($data, 200, ['Content-Type' => 'application/hal+json']);
    }

    /**
     * @param Phone $phone
     * @return Response
     *
     * @Route ("api/phones/{id}", name = "details_phone", methods = {"GET"})
     *
     * @SWG\Get(
     *      description="Endpoint for the details of a specific phone",
     *      produces={"application/hal+json"},
     * )
     *
     *  @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      description="id of the phone",
     *      required=true,
     *      type="integer",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns details of specific phone",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=Phone::class, groups={"details"}))
     *          )
     * )
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
     *  @SWG\Tag(name="Phone")
     *  @Security(name="Bearer")
     */
    public function details(Phone $phone): Response
    {
        $data = $this->serializer->serialize($phone, 'json', SerializationContext::create()->setGroups(['list', 'details']));

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }
}
