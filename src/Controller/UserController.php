<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends AbstractController
{
	/**
	 * @param Request $request
	 * @Route("/api/user", name="users")
	 */
	public function users(Request $request, SerializerInterface $serializer, UserRepository $userRepository)
	{
		// get all users
		if ($request->isMethod("get")) {
			$users = $this->getDoctrine()->getRepository(User::class)->findAll();
			$data = $serializer->serialize($users, 'json', ['groups' => 'group1']);
			return new JsonResponse($data, 200, [], true);
		} else if ($request->isMethod("post")) {
			// create a new user
			$data = $request->getContent();
			if (!empty($data)) {
				$data = json_decode($data, true);;
				if ($data["username"] && $data["password"]) {
					if ($this->getDoctrine()->getRepository(User::class)->findBy(["username" => $data["username"]])) {
						return new JsonResponse("user already exists !", 200, ['Content-Type:' => 'application/json'], false);
					}
					$user = new User();
					$user->setUsername($data["username"]);
					$user->setPassword(hash("sha256", $data["password"]));
					$user->setRoles(['ROLE_PRO']);
					$user->setApiToken(uniqid());
					$this->getDoctrine()->getManager()->persist($user);
					$this->getDoctrine()->getManager()->flush();
					$data = $serializer->serialize($user, 'json', ['groups' => 'group1']);
					return new JsonResponse($data, 201, ['Content-Type:' => 'application/json'], true);
				} else {
					return new JsonResponse(["error, wrong formated", $data], 500, ['Content-Type:' => 'application/json'], false);
				}
			} else {
				return new JsonResponse(["error, wrong formated", $data], 500, ['Content-Type:' => 'application/json'], false);
			}
		}
	}

	/**
	 * @Route("/api/user/{id_user}", name="user")
	 */
	public function userById(Request $request, $id_user, SerializerInterface $serializer, UserRepository $userRepository)
	{
		// get a single user
		if ($request->isMethod("get")) {
			$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id_user]);
			$data = $serializer->serialize($user, 'json', ['groups' => 'group1']);
			return new JsonResponse($data, 200, [], false);
		} else if ($request->isMethod("put")) {
			// update a single user
			$data = json_decode($request->getContent(), true);
			if ($data["password"]) {
				$user = $userRepository->find($id_user);
				$user->setPassword(hash("sha256", $data["password"]));
				$this->getDoctrine()->getManager()->persist($user);
				$this->getDoctrine()->getManager()->flush();
			}
			return new JsonResponse("user updated !", 200, [], false);
		} else if ($request->isMethod("delete")) {
			// delete a single user
			$user = $userRepository->find($id_user);
			$data = $serializer->serialize($user, 'json', ['groups' => 'group1']);
			$this->getDoctrine()->getManager()->remove($user);
			$this->getDoctrine()->getManager()->flush();
			return new JsonResponse($data, 200, [], true);
		}
		return new JsonResponse("error, wrong formated", 200, [], false);
	}
}
