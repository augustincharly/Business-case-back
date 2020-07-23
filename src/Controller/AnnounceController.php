<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Announce;
use App\Repository\AnnounceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class AnnounceController extends AbstractController
{
	/**
	 * @Route("/api/announce", name="announces")
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
	 * @Route("/api/announce/{id_announce}", name="announce")
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
