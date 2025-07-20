<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/publish', name: 'publish', methods: ['POST'])]
    public function publish(Request $request, HubInterface $hub): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $message = $data['message'] ?? '';

        $hub->publish([
            'data' => json_encode(['message' => $message]),
            'topics' => ['https://example.com/chat'],
        ]);

        return new JsonResponse(['status' => 'Message sent']);
    }
}