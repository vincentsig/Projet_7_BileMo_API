<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;



class SecurityController extends AbstractController
{
    /**
     * lexik_jwt authentication (see the configuration)
     * @Route("/api/login_check", name="login", methods={"POST"})
     * 
     * @SWG\Post(
     *     description="Authentication of the Company and get an access token",
     *     tags = {"Authentication"},
     * 
     * @SWG\Parameter(
     *     name="Body",
     *     required= true,
     *     in="body",
     *     type="string",
     *     description="Use your email and password",
     *     @SWG\Schema(
     *          type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="email", type="string"),
     *                  @SWG\Property(property="password", type="string"),
     *              ),
     *          )
     *      )
     * )
     * 
     * @SWG\Response(
     *      response="200",
     *      description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="token", type="string"),
     *              ),
     *          )
     *     ),    
     * @SWG\Response(
     *     response=401,
     *     description="Unauthorized: Returned when the JWT Token is not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not Found: Returned when the route is invalid",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Internal Server error: Returned when there is an Internal Server error",
     * )
     *     
     */
    public function login()
    {
    }
}
