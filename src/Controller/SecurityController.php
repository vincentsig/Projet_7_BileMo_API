<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;



class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login_check", name="login", methods={"POST"})
     * 
     * @SWG\Post(
     *     description="Authentication Company and get access token",
     *     tags = {"Authentication"},
     *     @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="token", type="string"),
     *              ),
     *          )
     *     ),    
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized: Bad credentials",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found: Invalid Route",
     *     ),
     *  @SWG\Response(
     *         response="500",
     *         description="Internal Server Error : An error occurred and your request couldn't be completed. Please try again or contact the Admin.",
     *     ),
     *     @SWG\Parameter(
     *          name="Body",
     *          required= true,
     *          in="body",
     *          type="string",
     *          description="Use your email and password",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="email", type="string"),
     *                  @SWG\Property(property="password", type="string"),
     *              ),
     *          )
     *      )
     * )
     * 
     */
    public function login()
    {
        // lexik_jwt authentication

    }
}
