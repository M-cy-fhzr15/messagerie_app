<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebSocketController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function index(): Response
    {
        return $this->render('chat/index.html.twig');
    }
}
