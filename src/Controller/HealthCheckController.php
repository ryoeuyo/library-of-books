<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/health')]
class HealthCheckController extends AbstractController
{
    #[Route('/', name: 'healthcheck')]
    public function healthCheck(): Response
    {
        return $this->json([
            'success' => true,
            'current_time' => date_create()->format('Y-m-d H:i:s'),
        ]);
    }
}
