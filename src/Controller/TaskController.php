<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TasksList;
use App\Form\TaskType;
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
     * @Route("/taskslistsshow/{id}", name="task_list", methods={"GET"})
     *
     * @return Response
     */
    public function showdata($id): Response
    {
        $list = $this->getList($id);
        $tasks = $this->getTasksByListId($list->getId());
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'list' => $list,

        ]);

    }

    /**
     * @Route("/tasks/create/{tl_id}", name="task_create")
     * @param Request $request
     * @param TasksList $tl
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, $tl_id): Response
    {

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        //$form->add('')
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $tlist = $this->getList($tl_id);
            $task->setUser($user);
            $task->setTasksList($tlist);

            $manager->persist($task);
            $manager->flush();

            $this->addFlash(
                'success',
                'The task has been added. '
            );

            return $this->redirectToRoute('task_list', array('id' => $tl_id));
        }

        return $this->render(
            'task/create.html.twig', [
                'taskForm' => $form->createView(),
                'tlid' => $tl_id,
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

        $lid = $task->getTasksList()->getId();

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash(
                'success',
                'Task has been modified. '
            );

            return $this->redirectToRoute('task_list', array('id' => $lid));
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

        $lid = $task->getTasksList()->getId();

        $this->addFlash(
            'success',
            'Task '.$name .' have been completed. ');

        return $this->redirectToRoute('task_list', array('id' => $lid));
    }

    /**
     * @Route("/task/{id}/delete", name="task_delete")
     *
     * @param Task $task
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function delete(Task $task, EntityManagerInterface $manager, Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lid = $task->getTasksList()->getId();

            $manager->remove($task);


            $manager->flush();
            $this->addFlash(
                'success',
                'Task has been deleted.'
            );


            return $this->redirectToRoute('task_list', array('id' => $lid));
        }

        $val = $form->createView();

        return $this->render('task/delete.html.twig', [
            'taskForm' => $val,
            'tlid' => $id,
        ]);
    }

    private function getList($id)
    {
        $lists = $product = $this->getDoctrine()
            ->getRepository(TasksList::class)
            ->find($id);
        return $lists;
    }

    private function getTasksByListId($id)
    {
        $lists = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy(array('tasksList' => $id));
        return $lists;
    }
}
