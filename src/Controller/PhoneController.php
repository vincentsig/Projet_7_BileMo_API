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

class PhoneController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route ("api/phones", name = "list_phones", methods={"GET"})
     */
    public function List(Request $request, PhoneRepository $repo): Response
    {
        $phones = $repo->findAll();

        $data = $this->serializer->serialize($phones, 'json', SerializationContext::create()->setGroups(['list']));
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route ("api/phones/{id}", name = "detail_phone", methods = {"GET"})
     */
    public function Details(Phone $phone, Request $request, PhoneRepository $repo): Response
    {
        $phoneDetails = $repo->find($phone->getId());
        $data = $this->serializer->serialize($phoneDetails, 'json', SerializationContext::create()->setGroups(['list', 'details']));

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }
}
