<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/answer')]
final class AnswerController extends AbstractController
{
    #[Route(name: 'app_answer_index', methods: ['GET'])]
    public function index(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', [
            'answers' => $answerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_answer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answer_show', methods: ['GET'])]
    public function show(Answer $answer): Response
    {
        return $this->render('answer/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_answer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answer_delete', methods: ['POST'])]
    public function delete(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $answer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/choose_answer', name: 'choose_answer', methods: ['POST'])]
    public function chooseAnswer(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the submitted data
        $userId = $request->request->get('user_id');
        $questionId = $request->request->get('question_id');
        $selectedAnswer = $request->request->get('answer');

        // Fetch the user and question entities based on the IDs submitted
        $user = $entityManager->getRepository(User::class)->find($userId);
        $question = $entityManager->getRepository(Question::class)->find($questionId);

        // Check if the user or question was not found
        if (!$user || !$question) {
            throw $this->createNotFoundException('User or Question not found.');
        }

        // Create a new Answer entity
        $answer = new Answer();

        // Set the actual User and Question entities
        $answer->setUser($user); // Set the actual User object
        $answer->setQuestion($question); // Set the actual Question object
        $answer->setChosenAnswer($selectedAnswer); // Set the chosen answer
        $answer->setTimestamp(new \DateTime()); // Set the current timestamp

        dd($answer);

        // Persist the answer to the database
        $entityManager->persist($answer);
        $entityManager->flush();

        // Check if the answer is correct
        $isCorrect = $selectedAnswer === $question->getCorrectAnswer();

        // Provide feedback to the user
        $message = $isCorrect ? 'Correct!' : 'Wrong answer. Try again!';

        // Add flash message for feedback
        $this->addFlash('info', $message);

        // Redirect to a different route (e.g., to show the next question or to the index)
        return $this->redirectToRoute('index');
        // return dd($answer);
    }
}
