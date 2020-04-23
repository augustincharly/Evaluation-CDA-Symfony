<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectStatusType;
use App\Form\ProjectType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Form\TaskType;

class ProjectController extends AbstractController
{
    /**
     * @Route("/admin/projects", name="projects")
     */
    public function list()
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
        return $this->render('project/projects.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/admin/new_project", name="new_project")
     */
    public function newProject(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $project->setStartedAt(new DateTime());
            $project->setStatus("Nouveau");
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("projects");
        }

        return $this->render('project/new_project.html.twig', [
            'projectForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/project/{project_id}", name="project")
     */
    public function projectDetails(Request $request, $project_id)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $project_id]);
        $form = $this->createForm(ProjectStatusType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project->setStatus($form->getData()['statut']);
            if ($form->getData()['statut'] == 'terminé') {
                $project->setEndedAt(new DateTime());
            }
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('project/project.html.twig', [
            'project' => $project,
            'project_status_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/project/new_task/{project_id}", name="new_task")
     */
    public function newTask(Request $request, $project_id)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setProject($this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $project_id]));
            $task->setCreatedAt(new DateTime());
            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("project", ['project_id' => $project_id]);
        }

        return $this->render('project/new_task.html.twig', [
            'taskForm' => $form->createView()
        ]);
    }
}
