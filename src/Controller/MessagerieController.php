<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{
    #[Route('/messagerie', name: 'messagerie')]
    public function index(Request $request, EntityManagerInterface $em, HubInterface $hub): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTimeImmutable());
            $em->persist($message);
            $em->flush();

            $update = new Update(
                '/messages',
                json_encode([
                    'expediteur' => $message->getExpediteur(),
                    'contenu' => $message->getContenu()
                ])
            );
            $hub->publish($update);

            $this->addFlash('success', 'Message envoyé');
        }

        return $this->render('messagerie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
