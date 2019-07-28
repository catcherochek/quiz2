<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository(Task::class)->findAll(),
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $task->setUser($user);

            $manager->persist($task);
            $manager->flush();

            $this->addFlash(
                'success',
                'The task has been added. '
            );

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/create.html.twig', [
                'taskForm' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/task/{id}/edit", name="task_edit")
     * @param Task $task
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Task $task, Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash(
                'success',
                'Task has been modified. '
            );

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'taskForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/task/{id}/toggle", name="task_toggle")
     *
     * @param Task $task
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function toggleTask(Task $task, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('TOGGLE', $task);

        $task->toggle(!$task->getIsDone());
        $manager->flush();

        $name = $task->getName();

        $this->addFlash(
            'success',
            'Task '.$name .' have been completed. ');

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/task/{id}/delete", name="task_delete")
     *
     * @param Task $task
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function delete(Task $task, EntityManagerInterface $manager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($task);
            $manager->flush();

            $this->addFlash(
                'success',
                'Task has been deleted.'
            );

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/delete.html.twig', [
            'taskForm' => $form->createView()
        ]);
    }
}
