<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Admin;
use App\Entity\Question;
use App\Service\QuestionService;

class AdminController extends AbstractController
{
    public function login()
    {
        return $this->render('admin-panel.html.twig');
    }

    public function adminPanel(Request $request, EntityManagerInterface $entityManager, QuestionService $questionService)
    {
        $username = $request->request->get('username');

        $password = $request->request->get('password');

        $dbRecord = $entityManager -> getRepository(Admin::class) -> findOneBy(['username'=>$username]);

        if ($dbRecord && md5($password)==$dbRecord->getPassword()) {
            $data['questions'] = $questionService->getQuestions();

            return $this->render('approval.html.twig', $data);
        } else {
            return new Response('Kullanıcı Adı veya Parola Hatalı!');
        }
        $this->getUser();
    }

    public function approval(QuestionService $questionService)
    {
        $data['questions'] = $questionService -> getActive();

        return $this->render('approval.html.twig', $data);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        return new Response();
    }

    public function deleteQuestion(int $id, EntityManagerInterface $entityManager, QuestionService $questionService)
    {
        $question = $entityManager->getRepository(Question::class)->find($id);
        $entityManager->remove($question);
        $entityManager->flush();
        $data['questions']=$questionService->getQuestions();
        
        return $this->render('approval.html.twig', $data);
    }

    public function updateTrueQuestion(int $id, EntityManagerInterface $entityManager, QuestionService $questionService)
    {
        $question = $entityManager->getRepository(Question::class)->find($id);
        $question->setStatus(true);
        $entityManager->persist($question);
        $entityManager->flush();
        $data['questions']=$questionService->getQuestions();

        return $this->render('approval.html.twig', $data);
    }

    public function updateFalseQuestion(int $id, EntityManagerInterface $entityManager, QuestionService $questionService)
    {
        $question = $entityManager->getRepository(Question::class)->find($id);
        $question->setStatus(false);
        $entityManager->persist($question);
        $entityManager->flush();
        $data['questions']=$questionService->getQuestions();

        return $this->render('approval.html.twig', $data);
    }
}

?>




