<?php

namespace App\Controller\Web;

use App\Entity\User\Seller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/seller', name: 'seller_')]
#[IsGranted('ROLE_SELLER')]
class SellerController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(Request $request): Response
    {
        // Get JWT user info from request attributes (set by JWT listener)
        $jwtUser = $request->attributes->get('jwt_user');
        $jwtToken = $request->attributes->get('jwt_token');
        
        if (!$jwtUser) {
            throw $this->createAccessDeniedException('Access denied: Valid JWT token required.');
        }

        // Verify it's a seller
        if (!in_array('ROLE_SELLER', $jwtUser['roles'])) {
            throw $this->createAccessDeniedException('Access denied: Seller role required.');
        }

        // Mock data for demonstration - replace with actual data from your services
        $stats = [
            'totalEstimations' => 42,
            'pendingEstimations' => 8,
            'completedEstimations' => 34,
            'averageValue' => 15500
        ];

        $vehicles = [
            [
                'id' => 1,
                'brand' => 'BMW',
                'model' => 'Série 3',
                'year' => 2020,
                'imageUrl' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400&h=300&fit=crop',
                'status' => 'completed',
                'condition' => 'Excellent',
                'estimatedPrice' => 28500,
                'fuelType' => 'Diesel',
                'mileage' => 45000,
                'transmission' => 'Automatique',
                'createdAt' => new \DateTime('-2 days')
            ],
            [
                'id' => 2,
                'brand' => 'Audi',
                'model' => 'A4',
                'year' => 2019,
                'imageUrl' => 'https://images.unsplash.com/photo-1549924231-f129b911e442?w=400&h=300&fit=crop',
                'status' => 'pending',
                'condition' => 'Très bon',
                'estimatedPrice' => 0,
                'fuelType' => 'Essence',
                'mileage' => 62000,
                'transmission' => 'Manuelle',
                'createdAt' => new \DateTime('-1 day'),
                'progress' => 65
            ],
            [
                'id' => 3,
                'brand' => 'Mercedes',
                'model' => 'Classe C',
                'year' => 2021,
                'imageUrl' => 'https://images.unsplash.com/photo-1563720223428-76a8434d4c37?w=400&h=300&fit=crop',
                'status' => 'completed',
                'condition' => 'Excellent',
                'estimatedPrice' => 35200,
                'fuelType' => 'Hybride',
                'mileage' => 28000,
                'transmission' => 'Automatique',
                'createdAt' => new \DateTime('-3 days')
            ],
            [
                'id' => 4,
                'brand' => 'Volkswagen',
                'model' => 'Golf',
                'year' => 2018,
                'imageUrl' => 'https://images.unsplash.com/photo-1606152421802-db97b9c7a11b?w=400&h=300&fit=crop',
                'status' => 'pending',
                'condition' => 'Bon',
                'estimatedPrice' => 0,
                'fuelType' => 'Essence',
                'mileage' => 75000,
                'transmission' => 'Manuelle',
                'createdAt' => new \DateTime('-5 hours'),
                'progress' => 30
            ]
        ];

        $recentActivities = [
            [
                'type' => 'estimation_completed',
                'title' => 'Estimation terminée',
                'description' => 'BMW Série 3 - 28 500 €',
                'time' => '2 heures',
                'icon' => 'check'
            ],
            [
                'type' => 'estimation_started',
                'title' => 'Nouvelle estimation',
                'description' => 'Audi A4 - En cours',
                'time' => '4 heures',
                'icon' => 'clock'
            ],
            [
                'type' => 'report_generated',
                'title' => 'Rapport généré',
                'description' => 'Mercedes Classe C',
                'time' => '1 jour',
                'icon' => 'document'
            ],
            [
                'type' => 'estimation_completed',
                'title' => 'Estimation terminée',
                'description' => 'Volkswagen Golf - 18 200 €',
                'time' => '2 jours',
                'icon' => 'check'
            ]
        ];

        return $this->render('Seller/seller_dashboard.html.twig', [
            'stats' => $stats,
            'vehicles' => $vehicles,
            'recentActivities' => $recentActivities,
            'jwtUser' => $jwtUser
        ]);
    }

    #[Route('/estimations/new', name: 'estimation_new')]
    public function newEstimation(): Response
    {
        // TODO: Implement new estimation form
        return $this->render('Seller/estimation_new.html.twig');
    }

    #[Route('/estimations/history', name: 'estimation_history')]
    public function estimationHistory(): Response
    {
        // TODO: Implement estimation history page
        return $this->render('Seller/estimation_history.html.twig');
    }

    #[Route('/estimations/{id}', name: 'estimation_detail', requirements: ['id' => '\d+'])]
    public function estimationDetail(int $id): Response
    {
        // TODO: Implement estimation detail page
        return $this->render('Seller/estimation_detail.html.twig', [
            'estimationId' => $id
        ]);
    }

    #[Route('/reports', name: 'reports')]
    public function reports(): Response
    {
        // TODO: Implement reports page
        return $this->render('Seller/reports.html.twig');
    }

    #[Route('/settings', name: 'settings')]
    public function settings(): Response
    {
        // TODO: Implement seller settings page
        return $this->render('Seller/settings.html.twig');
    }
}
