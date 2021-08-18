<?php

namespace App\Controller;

use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index(QuestionService $questionService)
    {
        $data['questions'] = $questionService -> getActive();

        return $this->render('base.html.twig', $data);
    }
}