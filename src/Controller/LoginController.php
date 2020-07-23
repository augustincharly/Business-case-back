<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LoginController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/login", name="login")
     */
    public function login(Request $request, SerializerInterface $serializer,  UserRepository $userRepository)
    {
        if ($request->isMethod("post")) {
            $data = $request->getContent();
            if (!empty($data)) {
                $data = json_decode($data, true);
                $user = $userRepository->findOneBy(["username" => $data["username"]]);
                if ($data["username"] && $data["password"]) {
                    if (!$user) {
                        return new JsonResponse("User not found", 500, ['Content-Type:' => 'application/json'], false);
                    } else if ($user->getPassword() !== hash("sha256", $data["password"])) {
                        return new JsonResponse("Wrong password", 500, ['Content-Type:' => 'application/json'], false);
                    } else {
                        return new JsonResponse(["username" => $user->getUsername(), "roles" => $user->getRoles(), "api_token" => $user->getApiToken()], 200, ['Content-Type:' => 'application/json'], false);
                    }
                } else {
                    return new JsonResponse("Error, wrong formated", 500, ['Content-Type:' => 'application/json'], false);
                }
            } else {
                return new JsonResponse("Error, wrong formated", 500, ['Content-Type:' => 'application/json'], false);
            }
        }
    }
}
