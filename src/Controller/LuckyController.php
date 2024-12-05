<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
    /*
    El name es el nombre de la ruta que se utiliza para llamar a este controlador
    Por ejemplo, si tenemos una ruta con el nombre "numero_suerte",
    podemos llamar a este controlador desde otro controlador con la siguiente ruta:
    {{ path('numero_suerte') }}
    */
    #[Route('/lucky/number', name:'numero_suerte')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}