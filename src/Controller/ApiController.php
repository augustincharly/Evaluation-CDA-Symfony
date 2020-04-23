<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/projects", name="api_projects")
     */
    public function listProjects(Request $request, SerializerInterface $serializer)
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
        $data = [];
        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'started_at' => $project->getStartedAt(),
                'ended_at' => $project->getEndedAt(),
                'status' => $project->getStatus()
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/project/{project_id}", name="api_project")
     */
    public function projectDetails($project_id, Request $request, SerializerInterface $serializer)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $project_id]);
        $data = [];
        if (isset($project)) {
            $tasks = $project->getTasks();
            foreach ($tasks as $task) {
                $data[] = [
                    'id' => $task->getId(),
                    'title' => $task->getTitle(),
                    'description' => $task->getDescription(),
                    'created_at' => $task->getCreatedAt()
                ];
            }
        }

        return new JsonResponse($data);
    }
}
