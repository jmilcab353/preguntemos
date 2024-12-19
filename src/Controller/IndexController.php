<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch the active question based on current time
        $currentDateTime = new \DateTime();
        $question = $entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->where('q.beginTime <= :now')
            ->andWhere('q.endTime >= :now')
            ->setParameter('now', $currentDateTime)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $this->render('index.html.twig', [
            'question' => $question,
        ]);
    }
}
