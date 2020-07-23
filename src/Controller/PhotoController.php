<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Photo;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PhotoController extends AbstractController
{
    /**
     * @Route("/api/photo", name="photos")
     */
    public function photos(Request $request, SerializerInterface $serializer, PhotoRepository $photoRepository)
    {
        if ($request->getMethod('get')) {
            $photos = $photoRepository->findAll();
            $data = $serializer->serialize($photos, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    /**
     * @Route("/api/photo/{id_photo}", name="photo")
     */
    public function photoById(Request $request, $id_photo, SerializerInterface $serializer, PhotoRepository $photoRepository)
    {
        if ($request->getMethod('get')) {
            $photo = $photoRepository->findOneBy(['id' => $id_photo]);
            $data = $serializer->serialize($photo, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }
}
