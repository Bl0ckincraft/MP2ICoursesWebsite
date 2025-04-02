<?php

namespace App\Controller;

use App\Entity\CourseElementType;
use App\Entity\ExerciseType;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController{
    #[Route('/api/all', name: 'app_api_all')]
    public function index(EntityManagerInterface $em): Response
    {
        $json = [
            "subjects" => $em->getRepository(Subject::class)->findAll()
        ];


        return $this->json($json);
    }
}
