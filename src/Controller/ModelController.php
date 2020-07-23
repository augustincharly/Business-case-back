<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Model;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ModelController extends AbstractController
{
    /**
     * @Route("/api/model", name="models")
     */
    public function models(Request $request, SerializerInterface $serializer, ModelRepository $modelRepository)
    {
        // get all models
        if ($request->isMethod("get")) {
            $models = $modelRepository->findAll();
            $data = $serializer->serialize($models, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    /**
     * @Route("/api/model/{id_model}", name="model")
     */
    public function modelById(Request $request, $id_brand, SerializerInterface $serializer, ModelRepository $modelRepository)
    {
        // get one single model
        if ($request->isMethod("get")) {
            $model = $modelRepository->findOneBy(['id' => $id_brand]);
            $data = $serializer->serialize($model, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }
}
