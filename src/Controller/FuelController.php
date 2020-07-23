<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Fuel;
use App\Repository\FuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class FuelController extends AbstractController
{
	/**
	 * @Route("/api/fuel", name="fuels")
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
	 * @Route("/api/fuel/{id_fuel}", name="fuel")
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
