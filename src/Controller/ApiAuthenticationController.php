<?php


namespace App\Controller;


use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiAuthenticationController extends AbstractController
{
    /**
     * Auth with username and password.
     *
     * @Route(path="/authentication_token", methods={"GET"})
     */
    public function getToken(JWTTokenManagerInterface $jwtManager)
    {
        $user = $this->getUser();
        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}