<?php

namespace App\Controller;

use App\Bundles\MyMail;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/registration", name="app_register")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface       $manager
     *
     * @return Response
     */
    public function registration(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager): Response
    {

        
        $mailsender = new MyMail();
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            ))
            ;
            $user->setRoles(['ROLE_USER']);
            $random_value = random_int(10000, 100000000 );
            $user->setValidated($random_value);
            
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash(
                'success',
                'Your account has been created! check email for validation'
            );
            
            return $this->redirectToRoute('app_login');
        }
       
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/registration/confirmation/{uid}/{validator}", name="app_register_confirm")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface       $manager
     *
     * @return Response
     */
    public function confirmation(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager,
        $validator,$uid): Response
    {
        //2657401
      return "";  
    }
}
