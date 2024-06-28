<?php

namespace App\Controller;

use App\Service\PizzaServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends AbstractController
{
    public function __construct(private readonly PizzaServiceInterface $pizzaService)
    {
    }

    public function index(Request $request): Response
    {
        $pizzaId = $request->get('pizzaId');
        $pizza = $this->pizzaService->getPizza($pizzaId);

        return $this->render('order/order.html.twig', [
            'pizza' => $pizza
        ]);
    }

    public function showThankYouPage(): Response
    {
        return $this->render('order/thanks.html.twig');
    }
}