<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function list(UserRepository $userRepository)
    {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/role/{id}", name="change_user_role")
     *
     * @param $id
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function changeUserRole(
        $id,
        UserRepository $userRepository,
        EntityManagerInterface $manager): Response
    {
        $user = $userRepository->find($id);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->flush();

        $this->addFlash(
            'success',
            'Role changed'
        );

        return $this->redirectToRoute('user_list');
    }
}
