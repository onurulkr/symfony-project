<?php

namespace App\Service;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }
    public function getQuestions(): array
    {
        $questions = $this->entityManager->getRepository(Question::class)->findAll();


        return $questions;
    }

    public function getActive(): array
    {
        $questions = $this->entityManager->getRepository(Question::class)->findBy([
            'status' => true
        ]);


        return $questions;
    }
}
