<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Garage;
use App\Repository\GarageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class GarageController extends AbstractController
{
	/**
	 * @Route("/api/garage", name="garages")
	 */
	public function garages(Request $request, SerializerInterface $serializer, GarageRepository $garageRepository)
	{
		if ($request->getMethod('get')) {
			$garages = $garageRepository->findAll();
			$data = $serializer->serialize($garages, 'json', ['groups' => 'group1']);
			return new JsonResponse($data, 200, [], true);
		}
		return new JsonResponse("error, wrong format", 200, [], false);
	}

	/**
	 * @Route("/api/garage/{id_garage}", name="garage")
	 */
	public function garageById(Request $request, $id_garage, SerializerInterface $serializer, GarageRepository $garageRepository)
	{
		if ($request->getMethod('get')) {
			$garage = $garageRepository->findOneBy(['id' => $id_garage]);
			$data = $serializer->serialize($garage, 'json', ['groups' => 'group1']);
			return new JsonResponse($data, 200, [], true);
		}
		return new JsonResponse("error, wrong format", 200, [], false);
	}
}
