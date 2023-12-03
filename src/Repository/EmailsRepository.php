<?php

namespace App\Repository;

use App\Entity\Emails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmailsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emails::class);
    }

    public function getCountDay($startDate, $endDate): array
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection("alt");
        return $connection->prepare("SELECT COUNT(email_id) AS `date`, SUBSTRING(created_at, 1, 10) as `e-mail`
                            FROM `emails` 
                            WHERE created_at BETWEEN '$startDate' AND '$endDate'
                            GROUP BY DAY(created_at);
                            ")
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getCountWeekly($startDate, $endDate): array
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection("alt");
        return $connection->prepare("SELECT COUNT(email_id) AS `date`, SUBSTRING(created_at, 1, 10) as `e-mail`
                            FROM `emails` 
                            WHERE created_at BETWEEN '$startDate' AND '$endDate'
                            GROUP BY WEEK(created_at);
                            ")
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getCountMonth($startDate, $endDate): array
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection("alt");
        return $connection->prepare("SELECT COUNT(email_id) as `date`, SUBSTRING(created_at, 1, 7) as `e-mail`
                            FROM `emails` 
                            WHERE created_at BETWEEN '$startDate' AND '$endDate'
                            GROUP BY MONTH(created_at);
                            ")
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getCountYear($startDate, $endDate): array
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection("alt");
        return $connection->prepare("SELECT   created_at as `date`, count(email_id) as `e-mail`
                                          FROM    emails
                                          WHERE created_at BETWEEN '$startDate' AND '$endDate'
                                          GROUP BY  YEAR(created_at);
                            ")
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
