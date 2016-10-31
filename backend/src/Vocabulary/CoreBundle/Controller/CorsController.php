<?php

namespace Vocabulary\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsController extends FOSRestController
{
    public function corsAction(Request $request)
    {
        return $this->handleView(View::create([], Response::HTTP_OK));
    }
}
