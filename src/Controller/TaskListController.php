<?php

namespace App\Controller;


use App\Entity\TasksList;
use App\Form\TaskListType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TaskListController
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class TaskListController extends AbstractController
{
    /**
     * @Route("/tasklist", name="tasklist")
     */
    public function index()
    {
        return $this->render('tasklist/index.html.twig', [
            'controller_name' => 'TaskListController',
        ]);
    }

    /**
     * @Route("/tasklist/create", name="tasklist_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $tasklist = new TasksList();
        $form = $this->createForm(TaskListType::class, $tasklist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $tasklist->setUser($user);

            $manager->persist($tasklist);
            $manager->flush();

            //if ()
            $this->addFlash(
                'success',
                'The task has been added. '
            );

            return $this->redirectToRoute('tasklist');
        }

        return $this->render(
            'tasklist/create.html.twig', [
                'taskForm' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/tasklist/{id}/delete", name="tasklist_delete")
     *
     * @param TasksList $task
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function delete(TasksList $task, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(TaskListType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->remove($task);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Task list has been deleted.'
                );
            } catch (Exception $ex) {
                $this->addFlash(
                    'danger',
                    'Task list was not been deleted. Delete all tasks first'
                );
            }
            return $this->redirectToRoute('tasklist');
        }

        return $this->render('tasklist/delete.html.twig', [
            'taskForm' => $form->createView()
        ]);
    }

}
