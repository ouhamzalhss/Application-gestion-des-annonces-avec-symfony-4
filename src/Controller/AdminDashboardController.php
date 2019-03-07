<?php

namespace App\Controller;

use App\Service\DashboardService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager,DashboardService $dashbordService)
    {
        $stats = $dashbordService->getStats();
        $bestAds = $dashbordService->getBestAds();
        $worstAds = $dashbordService->getWorstAds();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
