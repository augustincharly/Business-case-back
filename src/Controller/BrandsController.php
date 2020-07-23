<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use App\Entity\Brand;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class BrandsController extends AbstractController
{
	/**
	 * @Route("/api/brand", name="brands")
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
	 * @Route("/api/brand/{id_brand}", name="brand")
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
}
