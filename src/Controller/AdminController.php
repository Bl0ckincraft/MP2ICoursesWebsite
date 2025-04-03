<?php
namespace App\Controller;

use App\Entity\CourseElementType;
use App\Entity\ExerciseType;
use App\Entity\Subject;
use App\Entity\Chapter;
use App\Entity\CourseElement;
use App\Entity\Exercise;
use App\Form\CourseElementTypeType;
use App\Form\ExerciseTypeType;
use App\Form\SubjectType;
use App\Form\ChapterType;
use App\Form\FCourseElementType;
use App\Form\FExerciseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $chapterCount = $em->getRepository(Chapter::class)->count();
        $exerciseCount = $em->getRepository(Exercise::class)->count();

        $exercises = $em->getRepository(Exercise::class)->findAll();
        $correctedCount = 0;
        foreach ($exercises as $exercise) {
            if (sizeof($exercise->getSolutions()) > 0)
                $correctedCount++;
        }

        $correctedPercent = round($exerciseCount == 0 ? 0 : ($correctedCount * 100 / $exerciseCount), 2);


        return $this->render('admin/dashboard_home.html.twig', [
            'subjects' => $subjects,
            'chapterCount' => $chapterCount,
            'exerciseCount' => $exerciseCount,
            'correctedPercent' => $correctedPercent
        ]);
    }

    // Gestion des types d'éléments de cours
    #[Route('/admin/course-element-types', name: 'admin_course_element_types')]
    public function courseElementTypes(EntityManagerInterface $em, Request $request): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $elementTypes = $em->getRepository(CourseElementType::class)->findAll();

        $newElementType = new CourseElementType();
        $form = $this->createForm(CourseElementTypeType::class, $newElementType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newElementType);
            $em->flush();

            return $this->redirectToRoute('admin_course_element_types');
        }

        return $this->render('admin/course_element_types.html.twig', [
            'elementTypes' => $elementTypes,
            'form' => $form->createView(),
            'page_title' => 'Types d\'éléments de cours',
            'subjects' => $subjects,
        ]);
    }

    // Gestion des types d'exercices
    #[Route('/admin/exercise-types', name: 'admin_exercise_types')]
    public function exerciseTypes(EntityManagerInterface $em, Request $request): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $exerciseTypes = $em->getRepository(ExerciseType::class)->findAll();

        $newExerciseType = new ExerciseType();
        $form = $this->createForm(ExerciseTypeType::class, $newExerciseType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newExerciseType);
            $em->flush();

            return $this->redirectToRoute('admin_exercise_types');
        }

        return $this->render('admin/exercise_types.html.twig', [
            'exerciseTypes' => $exerciseTypes,
            'form' => $form->createView(),
            'page_title' => 'Types d\'exercices',
            'subjects' => $subjects,
        ]);
    }

    // Gestion des sujets
    #[Route('/admin/subjects', name: 'admin_subjects')]
    public function subjects(EntityManagerInterface $em, Request $request): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();

        $newSubject = new Subject();
        $form = $this->createForm(SubjectType::class, $newSubject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newSubject);
            $em->flush();

            return $this->redirectToRoute('admin_subjects');
        }

        return $this->render('admin/subjects.html.twig', [
            'subjects' => $subjects,
            'form' => $form->createView(),
            'page_title' => 'Gestion des sujets'
        ]);
    }

    // Ajout d'un nouveau chapitre
    #[Route('/admin/chapter/new/{subjectId}', name: 'admin_chapter_new')]
    public function newChapter(EntityManagerInterface $em, Request $request, int $subjectId): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $subject = $em->getRepository(Subject::class)->find($subjectId);

        if (!$subject) {
            throw $this->createNotFoundException('Sujet non trouvé');
        }

        $chapter = new Chapter();
        $chapter->setSubject($subject);

        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($chapter);
            $em->flush();

            return $this->redirectToRoute('admin_chapter_edit', ['id' => $chapter->getId()]);
        }

        return $this->render('admin/chapter_new.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject,
            'page_title' => 'Nouveau chapitre',
            'subjects' => $subjects,
        ]);
    }

    // Édition d'un chapitre
    #[Route('/admin/chapter/{id}', name: 'admin_chapter_edit')]
    public function editChapter(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $chapter = $em->getRepository(Chapter::class)->find($id);

        if (!$chapter) {
            throw $this->createNotFoundException('Chapitre non trouvé');
        }

        $chapterForm = $this->createForm(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);

        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_chapter_edit', ['id' => $id]);
        }

        // Gestion des éléments de cours
        $newElement = new CourseElement();
        $elementForm = $this->createForm(FCourseElementType::class, $newElement);
        $elementForm->handleRequest($request);

        if ($elementForm->isSubmitted() && $elementForm->isValid()) {
            $newElement->setChapter($chapter);

            if ($request->get("new_el") == null) {
                $element = $em->getRepository(CourseElement::class)->find($request->get("edit_el_id"));
                $element->setChapter($newElement->getChapter());
                $element->setElementType($newElement->getElementType());
                $element->setStatement($newElement->getStatement());
                $element->setNumber($newElement->getNumber());
                $element->setDetails($newElement->getDetails());
                $element->setProofs($newElement->getProofs());
                $em->flush();
            } else {
                $em->persist($newElement);
                $em->flush();
            }

            return $this->redirectToRoute('admin_chapter_edit', ['id' => $id]);
        }

        // Gestion des exercices
        $newExercise = new Exercise();
        $exerciseForm = $this->createForm(FExerciseType::class, $newExercise);
        $exerciseForm->handleRequest($request);

        if ($exerciseForm->isSubmitted() && $exerciseForm->isValid()) {
            $newExercise->setChapter($chapter);

            if ($request->get("new_ex") == null) {
                $exercise = $em->getRepository(Exercise::class)->find($request->get("edit_ex_id"));
                $exercise->setExerciseType($newExercise->getExerciseType());
                $exercise->setChapter($newExercise->getChapter());
                $exercise->setTitle($newExercise->getTitle());
                $exercise->setHints($newExercise->getHints());
                $exercise->setSolutions($newExercise->getSolutions());
                $exercise->setNumber($newExercise->getNumber());
                $exercise->setStatement($newExercise->getStatement());
                $em->flush();
            } else {
                $em->persist($newExercise);
                $em->flush();
            }

            return $this->redirectToRoute('admin_chapter_edit', ['id' => $id]);
        }

        return $this->render('admin/chapter_edit.html.twig', [
            'chapter' => $chapter,
            'chapterForm' => $chapterForm->createView(),
            'elementForm' => $elementForm->createView(),
            'exerciseForm' => $exerciseForm->createView(),
            'page_title' => $chapter->getDisplayName(),
            'subjects' => $subjects,
        ]);
    }

    // Suppression d'un élément
    #[Route('/admin/course-element/delete/{id}', name: 'admin_course_element_delete', methods: ['POST'])]
    public function deleteCourseElement(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $element = $em->getRepository(CourseElement::class)->find($id);

        if (!$element) {
            throw $this->createNotFoundException('Élément non trouvé');
        }

        $chapterId = $element->getChapter()->getId();

        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em->remove($element);
            $em->flush();
        }

        return $this->redirectToRoute('admin_chapter_edit', ['id' => $chapterId]);
    }

    // Suppression d'un exercice
    #[Route('/admin/exercise/delete/{id}', name: 'admin_exercise_delete', methods: ['POST'])]
    public function deleteExercise(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $exercise = $em->getRepository(Exercise::class)->find($id);

        if (!$exercise) {
            throw $this->createNotFoundException('Exercice non trouvé');
        }

        $chapterId = $exercise->getChapter()->getId();

        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em->remove($exercise);
            $em->flush();
        }

        return $this->redirectToRoute('admin_chapter_edit', ['id' => $chapterId]);
    }

    #[Route('/admin/course-element-type/{id}/edit', name: 'admin_course_element_type_edit')]
    public function editCourseElementType(CourseElementType $elementType, Request $request, EntityManagerInterface $em): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $form = $this->createForm(CourseElementTypeType::class, $elementType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_course_element_types');
        }

        return $this->render('admin/course_element_type_edit.html.twig', [
            'form' => $form->createView(),
            'elementType' => $elementType,
            'subjects' => $subjects,
        ]);
    }

    #[Route('/admin/course-element-type/{id}/delete', name: 'admin_course_element_type_delete', methods: ['POST'])]
    public function deleteCourseElementType(CourseElementType $elementType, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$elementType->getId(), $request->request->get('_token'))) {
            $em->remove($elementType);
            $em->flush();
        }

        return $this->redirectToRoute('admin_course_element_types');
    }

    #[Route('/admin/subject/{id}/edit', name: 'admin_subject_edit')]
    public function editSubject(Subject $subject, Request $request, EntityManagerInterface $em): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_subjects');
        }

        return $this->render('admin/subject_edit.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject,
            'subjects' => $subjects,
        ]);
    }

    #[Route('/admin/subject/{id}/delete', name: 'admin_subject_delete', methods: ['POST'])]
    public function deleteSubject(Subject $subject, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $em->remove($subject);
            $em->flush();
        }

        return $this->redirectToRoute('admin_subjects');
    }

    #[Route('/admin/exercise-type/{id}/edit', name: 'admin_exercise_type_edit')]
    public function editExerciseType(ExerciseType $exerciseType, Request $request, EntityManagerInterface $em): Response
    {
        $subjects = $em->getRepository(Subject::class)->findAll();
        $form = $this->createForm(ExerciseTypeType::class, $exerciseType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_exercise_types');
        }

        return $this->render('admin/exercise_type_edit.html.twig', [
            'form' => $form->createView(),
            'exerciseType' => $exerciseType,
            'subjects' => $subjects,
        ]);
    }

    #[Route('/admin/exercise-type/{id}/delete', name: 'admin_exercise_type_delete', methods: ['POST'])]
    public function deleteExerciseType(ExerciseType $exerciseType, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exerciseType->getId(), $request->request->get('_token'))) {
            $em->remove($exerciseType);
            $em->flush();
        }

        return $this->redirectToRoute('admin_exercise_types');
    }
}