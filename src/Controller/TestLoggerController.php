<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestLoggerController extends AbstractController
{
    #[Route('/test-logger', name: 'test_logger')]
    public function testLogger(LoggerInterface $logger): Response
    {
        // Escribe un mensaje en el log
        $logger->info('Mensaje de prueba desde el controlador.');

        return new Response('El logger ha sido probado. Revisa los logs.');
    }
}
