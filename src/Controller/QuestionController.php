<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class QuestionController extends AbstractController
{
    #[Route('/question/new', name: 'create_question')]
    public function createQuestion(EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $question->setQuestionText('What is Symfony?');
        $question->setAnswerOne('A PHP Framework');
        $question->setAnswerTwo('A JavaScript Library');
        $question->setAnswerThree('A Database');
        $question->setAnswerFour('An Operating System');
        $question->setCorrectAnswer('1');
        $question->setBeginTime(new \DateTime('2024-12-20 08:00:00'));
        $question->setEndTime(new \DateTime('2024-12-20 12:00:00'));

        $entityManager->persist($question);
        $entityManager->flush();

        return new Response('Saved new question with id ' . $question->getId());
    }

    #[Route('/question/{id}', name: 'show_question', requirements: ['id' => '\d+'])]
    public function showQuestion(QuestionRepository $questionRepository, int $id): Response
    {
        $question = $questionRepository->find($id);


        dd($question);

        if (!$question) {
            throw $this->createNotFoundException('No question found for id ' . $id);
        }



        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/questions', name: 'list_questions')]
    public function listQuestions(
        QuestionRepository $questionRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $questionRepository->createQueryBuilder('q')->getQuery();

        $questions = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5 // Items per page
        );

        return $this->render('question/list.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/question/edit/{id}', name: 'edit_question', requirements: ['id' => '\d+'])]
    public function editQuestion(
        Request $request,
        EntityManagerInterface $entityManager,
        QuestionRepository $questionRepository,
        int $id
    ): Response {
        $question = $questionRepository->find($id);

        if (!$question) {
            throw $this->createNotFoundException('No question found for id ' . $id);
        }

        if ($request->isMethod('POST')) {
            $question->setQuestionText($request->request->get('questionText'));
            $question->setAnswerOne($request->request->get('answerOne'));
            $question->setAnswerTwo($request->request->get('answerTwo'));
            $question->setAnswerThree($request->request->get('answerThree'));
            $question->setAnswerFour($request->request->get('answerFour'));
            $question->setCorrectAnswer($request->request->get('correctAnswer'));

            $entityManager->flush();

            return $this->redirectToRoute('show_question', ['id' => $question->getId()]);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/question/delete/{id}', name: 'delete_question', requirements: ['id' => '\d+'])]
    public function deleteQuestion(
        EntityManagerInterface $entityManager,
        QuestionRepository $questionRepository,
        int $id
    ): Response {
        $question = $questionRepository->find($id);

        if (!$question) {
            throw $this->createNotFoundException('No question found for id ' . $id);
        }

        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('list_questions');
    }

    #[Route('/choose-answer', name: 'choose_answer', methods: ['POST'])]
    public function chooseAnswer(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the submitted data
        $questionId = $request->request->get('question_id');
        $selectedAnswer = $request->request->get('answer');

        // Fetch the question entity
        $question = $entityManager->getRepository(Question::class)->find($questionId);

        if (!$question) {
            throw $this->createNotFoundException('Question not found.');
        }

        // Check if the answer is correct
        $isCorrect = $selectedAnswer === $question->getCorrectAnswer();

        // Provide feedback to the user
        $message = $isCorrect ? 'Correct!' : 'Wrong answer. Try again!';

        // Redirect back to the index or another appropriate route
        $this->addFlash('info', $message);

        return $this->redirectToRoute('index');
    }
}
