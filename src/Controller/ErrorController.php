<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function show(FlattenException $exception): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'error' => $exception
        ]);
    }
}
