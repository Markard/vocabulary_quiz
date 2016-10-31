<?php

namespace Vocabulary\UserBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vocabulary\UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DefaultController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="User",
     *  description="Создаем нового пользователя если его еще нет. Возвращаем jwt token.",
     *  input={
     *     "class"="Vocabulary\UserBundle\Type\RegistrationType"
     *  },
     *  output={
     *     "class"="Vocabulary\UserBundle\Entity\User",
     *     "groups"={"registration"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
     *  statusCodes = {
     *      200={"Пользователь найден или создан."},
     *      400={"Произошла ошибка валидации."}
     *  }
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registrationAction(Request $request)
    {
        $typeClass = $this->container->getParameter('vocabulary.registration.type.class');
        $form = $this->createForm($typeClass, new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->container
                ->get('vocabulary.registration.handler')
                ->register($form->getData());

            $context = (new Context())->addGroup('registration');
            $view = View::create($user, Response::HTTP_CREATED)
                ->setContext($context);
        } else {
            $view = View::create(['form' => $form], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }
}
