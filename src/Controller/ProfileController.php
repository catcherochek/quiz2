<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConfirmPasswordType;
use App\Form\ProfileType;
use App\Form\PWDChangeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        return $this->render('profile/show.html.twig',['imgpath' =>$this->getParameter('foto_directory') ]);
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
            $brochureFile = $form['foto']->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                if (isset($brochureFile)) {
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                }
                
                // Move the file to the directory where brochures are stored
                $ppaatt = $this->getParameter('foto_directory');
                try {
                    $brochureFile->move(
                        $ppaatt,
                        $newFilename
                        );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
         $user->setFoto($newFilename);
         $em = $this->getDoctrine()->getManager();
         $em->flush();
         
            
            
            
            $this->addFlash('success', "Succesfully edited");

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
                ' pass incorrect'
            );

            return $this->redirectToRoute('profile_request_delete');
        }

        return $this->render('profile/requestDeleteProfile.html.twig', [
            'confirmPasswordToDeleteProfile' => $deleteUserForm->createView(),
        ]);
    }

    /**
     * @Route("/profile/{userId}/pwdchange", name="profile_pwdchange")
     *
     */
    public function passwordchanger(
        Request $request, $userId,
        EntityManagerInterface $em,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage)
    {
        $user = $this->getUser();

        $chUserForm = $this->createForm(PWDChangeFormType::class);
        $chUserForm->handleRequest($request);
        if ($user->getId() == $userId) {
            if ($chUserForm->isSubmitted() && $chUserForm->isValid()) {
                $i2 = 0;
            }
        }
        return $this->render('profile/requestPWDChangeProfile.html.twig', [
            'ChangePass' => $chUserForm->createView(),
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
