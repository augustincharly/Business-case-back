<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Announce;
use App\Entity\Brand;
use App\Repository\AnnounceRepository;
use App\Repository\BrandRepository;
use App\Repository\FuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PublicController extends AbstractController
{
    // Announce section

    /**
     * @Route("/public/announce", name="public_announces")
     */
    public function announces(Request $request, SerializerInterface $serializer, AnnounceRepository $announceRepository)
    {
        if ($request->getMethod('get')) {
            $announces = $announceRepository->findAll();
            $data = $serializer->serialize($announces, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    /**
     * @Route("/public/announce/{id_announce}", name="public_announce")
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

    // Brand Section

    /**
     * @Route("/public/brand", name="public_brands")
     */
    public function brands(Request $request, SerializerInterface $serializer, BrandRepository $brandRepository)
    {
        // get all brands
        if ($request->isMethod("get")) {
            $brands = $brandRepository->findAll();
            $data = $serializer->serialize($brands, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    /**
     * @Route("/public/brand/{id_brand}", name="public_brand")
     */
    public function brandById(Request $request, $id_brand, SerializerInterface $serializer, BrandRepository $brandRepository)
    {
        // get a single brand
        if ($request->isMethod("get")) {
            $brand = $brandRepository->findOneBy(['id' => $id_brand]);
            $data = $serializer->serialize($brand, 'json');
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    // Model Section

    /**
     * @Route("/public/model/{id_brand}", name="public_model")
     */
    public function modelByBrand(Request $request, $id_brand, SerializerInterface $serializer, BrandRepository $brandRepository)
    {
        // get all models of a brand
        if ($request->isMethod("get")) {
            $models = $brandRepository->findOneBy(['id' => $id_brand])->getModels();
            $data = $serializer->serialize($models, 'json');
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    // Fuel Section

    /**
     * @Route("/public/fuel", name="public_fuels")
     */
    public function fuels(Request $request, SerializerInterface $serializer, FuelRepository $fuelRepository)
    {
        if ($request->getMethod('get')) {
            $fuels = $fuelRepository->findAll();
            $data = $serializer->serialize($fuels, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }

    /**
     * @Route("/public/fuel/{id_fuel}", name="public_fuel")
     */
    public function fuelById(Request $request, $id_fuel, SerializerInterface $serializer, FuelRepository $fuelRepository)
    {
        if ($request->getMethod('get')) {
            $fuel = $fuelRepository->findOneBy(['id' => $id_fuel]);
            $data = $serializer->serialize($fuel, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse("error, wrong format", 200, [], false);
    }
}
