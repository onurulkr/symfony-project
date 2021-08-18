<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\Type\QuestionType;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\Category;
use App\Entity\Option;
use App\Entity\Answer;
use App\Form\Type\UserType;

class QuestionController extends AbstractController
{
    public function addQuestion(Request $request, EntityManagerInterface $entityManager)
    {
        $formData = $request->request->all();

        if (!$formData) {
            $data['categories'] = $entityManager->getRepository(Category::class)->findAll();
            $data['options'] = $entityManager->getRepository(Option::class)->findAll();

            return $this->render('question-form.html.twig', $data);
        } else {
            $question = new Question();
            $form = $this->createForm(QuestionType::class, $question);
            $form->submit([
            'name' =>$request->request->get('question')
        ]);
            $question->setStatus(false);

            $category = $entityManager->getRepository(Category::class)->findOneBy([
            'id' => $request->request ->get('category')
        ]);

            $question->setCategoryId($category);
            $entityManager->persist($question);

            $options = $entityManager->getRepository(Option::class)->findAll();

            foreach ($options as $option) {
                $answer = new Answer();
                $answer->setValue($request->request->get($option->getId()));
                $answer->setQuestion($question);
                $answer->setOptions($option);

                if ($request->request->get('correctAnswer') == $option->getId()) {
                    $answer->setIsCorrectAnswer(true);
                } else {
                    $answer->setIsCorrectAnswer(false);
                }
                $entityManager->persist($answer);
            }

            $email = $request->request->get('email');
            $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

            if (!$user) {
                $user = new User();
                $form = $this->createForm(UserType::class, $user);
                $form->submit([
                'name' =>$request->request->get('name'),
                'surname' =>$request->request->get('surname'),
                'email' => $request->request->get('email')

            ]);
                $entityManager->persist($user);
            }

            $question->setUser($user);
            $entityManager->persist($question);
            $entityManager->flush();

            return new Response('Sorunuz Başarılı Şekilde Gönderildi!');
        }
    }
}