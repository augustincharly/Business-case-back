<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Announce;
use App\Repository\AnnounceRepository;
use App\Repository\UserRepository;
use App\Repository\ProfessionalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProController extends AbstractController
{
    // Announce section

    /**
     * @Route("/professional/announce", name="professional_announces")
     */
    public function announces(Request $request, SerializerInterface $serializer, AnnounceRepository $announceRepository, UserRepository $userRepository, ProfessionalRepository $professionalRepository)
    {
        if ($request->getMethod('get')) {
            $requestUser = $userRepository->findOneBy(["api_token" => $request->headers->get('X-AUTH-TOKEN')]);
            $requestPro = $professionalRepository->findProByUserId($requestUser->getId());
            $announces = $announceRepository->findByProId($requestPro->getId());
            $data = $serializer->serialize($announces, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    /**
     * @Route("/professional/announce/{id_announce}", name="professional_announce")
     */
    public function announceById(Request $request, $id_announce, SerializerInterface $serializer, AnnounceRepository $announceRepository)
    {
        if ($request->getMethod('get')) {
            $announce = $announceRepository->findOneBy(['id' => $id_announce]);
            $data = $serializer->serialize($announce, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }
}
