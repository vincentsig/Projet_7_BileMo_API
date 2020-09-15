<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;


class PhoneController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route ("api/phones", name = "list_phones", methods={"GET"})
     * @return Response
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
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=Phone::class, groups={"list"}))
     *          )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     *  @SWG\Tag(name="Phone")
     */
    public function List(Request $request, PhoneRepository $repo): Response
    {
        $phones = $repo->findAll();

        $data = $this->serializer->serialize($phones, 'json', SerializationContext::create()->setGroups(['list']));
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route ("api/phones/{id}", name = "detail_phone", methods = {"GET"})
     * @param $id
     * @return Response
     * 
     * @SWG\Get(
     *      description="Endpoint for the details of a specific phone",
     *      produces={"application/hal+json"},
     * )
     *
     *  @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id of the phone",
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
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     *  @SWG\Tag(name="Phone")
     */
    public function Details(Phone $phone, Request $request, PhoneRepository $repo): Response
    {
        $phoneDetails = $repo->find($phone->getId());
        $data = $this->serializer->serialize($phoneDetails, 'json', SerializationContext::create()->setGroups(['list', 'details']));

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }
}