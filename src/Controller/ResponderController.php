<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResponderController extends AbstractController {

    // TODO    
    #[Route('/choose_answer', name: 'choose_answer', methods: ['POST'])]
    public function chooseAnswer(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Decode JSON payload
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            dd('Invalid payload', $request->getContent());
            $this->addFlash('error', 'Datos recibidos inválidos.');
            return $this->redirectToRoute('/');
        }

        $user = $this->getUser();
        $questionId = $data['question_id'] ?? null;
        $selectedAnswer = $data['answer'] ?? null;

        // Validate required fields
        if (!$user || !$questionId || !$selectedAnswer) {
            $this->addFlash('error', 'Faltan datos requeridos.');
            return $this->redirectToRoute('/');
        }

        // Retrieve Question entity
        $question = $entityManager->getRepository(Question::class)->find($questionId);
        if (!$question) {
            $this->addFlash('error', 'Pregunta no encontrada.');
            return $this->redirectToRoute('/');
        }

        // Create and persist the Answer entity
        $answer = new Answer();
        $answer->setUser($user);
        $answer->setQuestion($question);
        $answer->setChosenAnswer((string)$selectedAnswer);
        $answer->setTimestamp(new \DateTime());

        $entityManager->persist($answer);
        $entityManager->flush();

        $this->addFlash('success', 'Respuesta enviada con éxito.');
        return $this->redirectToRoute('/');
    }

}

    