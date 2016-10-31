<?php

namespace Vocabulary\QuizBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vocabulary\QuizBundle\Entity\Quiz;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class QuizController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="Quiz",
     *  description="Создаем новый тест.",
     *  headers={
     *      {
     *           "name"="Authorization",
     *           "description"="Bearer token",
     *           "required"=true
     *      }
     *  },
     *  output={
     *     "class"="Vocabulary\QuizBundle\Entity\Quiz",
     *     "groups"={"create"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
     *  statusCodes = {
     *      200={"Тест был создан."},
     *      401={"Не удалось аутентифицировать в системе."},
     *  }
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postQuizAction(Request $request)
    {
        $quiz = $this->container
            ->get('vocabulary.quiz.handler')
            ->create($this->getParameter('max_quiz_fails_number'), $this->getParameter('max_quiz_questions_number'));

        $context = (new Context())->addGroup('create');
        $view = View::create($quiz, Response::HTTP_CREATED)
            ->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="Quiz",
     *  description="Получаем информацию о тесте.",
     *  headers={
     *      {
     *          "name"="Authorization",
     *          "description"="Bearer token",
     *          "required"=true
     *      }
     *  },
     *  output={
     *     "class"="Vocabulary\QuizBundle\Entity\Quiz",
     *     "groups"={"get"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
     *  statusCodes = {
     *      200={"Данные о тесте упешно полученны."},
     *      401={"Не удалось аутентифицировать в системе."},
     *      404={"Тест не найден."},
     *  }
     * )
     *
     * @ParamConverter("quiz", class="VocabularyQuizBundle:Quiz")
     *
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function getQuizAction(Quiz $quiz)
    {
        $context = (new Context())->addGroup('get');
        $view = View::create($quiz, Response::HTTP_OK)->setContext($context);

        return $this->handleView($view);
    }
}
