<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConfirmPasswordType;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class ProfileController
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile_show")
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('profile/show.html.twig');
    }

    /**
     * @Route("/profile/{id}/edit", name="profile_edit")
     *
     * @param User $user
     * @param Request $request
     *
     * @return Response
     */
    public function edit(
        User $user,
        Request $request
    ): Response
    {
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('profile_show');
        }

        $em = $this->getDoctrine()->getManager();
        $em->refresh($user);

        return $this->render('profile/edit.html.twig', [
            'profileForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/delete/{userId}", name="delete_profile")
     *
     */
    public function deleteUser(
        Request $request, $userId,
        EntityManagerInterface $em,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage)
    {
        $user = $this->getUser();
        $deleteUserForm = $this->createForm(ConfirmPasswordType::class);
        $deleteUserForm->handleRequest($request);

        if ($request->isXmlHttpRequest() && $user->getId() === $userId) {

            if ($deleteUserForm->isSubmitted() && $deleteUserForm->isValid()) {

                // force manual logout of logged in user
                $this->get('security.token_storage')->setToken(null);

                $em->remove($user);
                $em->flush();

                $session->invalidate(0);

                return $this->redirectToRoute('homepage');
            }

            $this->addFlash(
                'warning',
                'Mot de pass incorrect'
            );

            return $this->redirectToRoute('profile_request_delete');
        }

        return $this->render('profile/requestDeleteProfile.html.twig', [
            'confirmPasswordToDeleteProfile' => $deleteUserForm->createView(),
        ]);
    }

    /**
     * @Route("/profile/request/delete", name="profile_request_delete")
     *
     * @return Response
     */
    public function requestDeleteUser(): Response
    {
        return $this->render('profile/requestDeleteProfile.html.twig');
    }
}
