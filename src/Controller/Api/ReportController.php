<?php

namespace App\Controller\Api;


use App\Traits\DateTimeValidate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmailsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/email-report")
 */
class ReportController extends AbstractController
{
    private $emailsRepository;
    use DateTimeValidate;

    public function __construct(EmailsRepository $emailsRepository)
    {
        $this->emailsRepository = $emailsRepository;
    }

    /**
     * @Route("", name="email_report", methods={"POST"})
     */
    public function index(Request $request): JsonResponse
    {
        $jsonData = json_decode($request->getContent(), true);
        $period = $jsonData['period'];
        $startDate = $jsonData['date_range']['start'];
        $endDate = $jsonData['date_range']['end'];
        if($this->validateDate($startDate) and $this->validateDate($endDate) and is_string($period)){
            return $this->json($this->routeData($period, $startDate, $endDate));
        }else{
            return $this->json(null);
        }

    }

    public function routeData($period, $startDate, $endDate): array
    {
        switch ($period) {
            case "daily":
                return  $this->emailsRepository->getCountDay($startDate, $endDate);
            case "weekly":
                return  $this->emailsRepository->getCountWeekly($startDate, $endDate);
            case "monthly":
                return  $this->emailsRepository->getCountMonth($startDate, $endDate);
            case "yearly":
                return  $this->emailsRepository->getCountYear($startDate, $endDate);
        }
    }
}
