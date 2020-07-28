<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Professional;
use App\Entity\User;
use App\Entity\Announce;
use App\Repository\ProfessionalRepository;
use App\Repository\UserRepository;
use App\Repository\AnnounceRepository;
use App\Repository\GarageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdminController extends AbstractController
{
    // Pro section

    /**
     * @param Request $request
     * @Route("/admin/pro", name="admin_pros")
     */
    public function professionals(
        Request $request,
        SerializerInterface $serializer,
        ProfessionalRepository $professionalRepository,
        UserRepository $userRepository
    ) {
        // get all pros
        if ($request->isMethod("get")) {
            $pros = $this->getDoctrine()->getRepository(Professional::class)->findAll();
            $data = $serializer->serialize($pros, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        } else if ($request->isMethod("post")) {
            // create a new pro
            $data = $request->getContent();
            if (!empty($data)) {
                $data = json_decode($data, true);
                if (
                    $data["user_id"] && $data["firstname"]
                    && $data["lastname"]
                    && $data["email"]
                    && $data["tel"]
                    && $data["siret"] && !$professionalRepository->findProByUserId($data["user_id"])
                ) {
                    if (!$userRepository->find($data["user_id"])) {
                        return new JsonResponse("user not found", 404, ['Content-Type:' => 'application/json'], false);
                    }
                    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $data["user_id"]]);
                    $pro = new Professional();
                    $pro->setUser($user);
                    $pro->setFirstname($data["firstname"]);
                    $pro->setLastname($data["lastname"]);
                    $pro->setEmail($data["email"]);
                    $pro->setTel($data["tel"]);
                    $pro->setSiret($data["siret"]);
                    $this->getDoctrine()->getManager()->persist($pro);
                    $this->getDoctrine()->getManager()->flush();
                    return new JsonResponse("professional created !", 201, ['Content-Type:' => 'application/json'], false);
                } else {
                    return new JsonResponse(["error, wrong formated"], 500, ['Content-Type:' => 'application/json'], false);
                }
            } else {
                return new JsonResponse(["error, wrong formated"], 500, ['Content-Type:' => 'application/json'], false);
            }
        }
    }

    /**
     * @Route("/admin/pro/{id_pro}", name="admin_pro")
     */
    public function professionalById(Request $request, $id_pro, SerializerInterface $serializer, ProfessionalRepository $professionalRepository)
    {
        // get a single pro
        if ($request->isMethod("get")) {
            $pro = $this->getDoctrine()->getRepository(Professional::class)->findOneBy(['id' => $id_pro]);
            $data = $serializer->serialize($pro, 'json', ['groups' => 'group1']);
            return new JsonResponse($data, 200, [], true);
        } else if ($request->isMethod("put")) {
            // update a single pro
            $data = json_decode($request->getContent(), true);
            $pro = $professionalRepository->find($id_pro);
            if ($data["firstname"]) {
                $pro->setFirstname($data["firstname"]);
            }
            if ($data["lastname"]) {
                $pro->setLastname($data["lastname"]);
            }
            if ($data["email"]) {
                $pro->setEmail($data["email"]);
            }
            if ($data["tel"]) {
                $pro->setTel($data["tel"]);
            }
            if ($data["siret"]) {
                $pro->setSiret($data["siret"]);
            }
            $this->getDoctrine()->getManager()->persist($pro);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse("professional updated !", 200, [], false);
        } else if ($request->isMethod("delete")) {
            // delete a single pro
            $pro = $professionalRepository->find($id_pro);
            $data = $serializer->serialize($pro, 'json', ['groups' => 'group1']);
            $this->getDoctrine()->getManager()->remove($pro);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(
                $data,
                200,
                [],
                true
            );
        }
        return new JsonResponse("error, wrong formated", 200, [], false);
    }

    // User section

    /**
     * @param Request $request
     * @Route("/admin/user", name="admin_users")
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
                        return new JsonResponse("user already exists !", 500, ['Content-Type:' => 'application/json'], false);
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
     * @Route("/admin/user/{id_user}", name="admin_user")
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

    // Announce section

    /**
     * @Route("/admin/announce", name="admin_announces")
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
     * @Route("/admin/announce/{id_announce}", name="admin_announce")
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

    // Garage Section

    /**
     * @Route("/admin/garage", name="admin_garages")
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
     * @Route("/admin/garage/{id_garage}", name="admin_garage")
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
