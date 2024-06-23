<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends AbstractController
{
    public function __construct()
    {
    }

    public function index(): Response
    {
        return $this->render('order/order.html.twig');
    }
}