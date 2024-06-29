<?php

namespace App\Controller;

use App\Service\PizzaServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        SessionController::putPizzaInStorage($pizzaId);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    public function basket(): Response
    {
        $data = [];
        $pizzas = SessionController::takePizzasFromStorage();

        foreach ($pizzas as $key => $value)
        {
            $pizza = $this->pizzaService->getPizza($key);
            $data[] = [$pizza, $value];
        }
        return $this->render('basket/basket.html.twig', [
            'pizzas' => $data
        ]);
    }

    public function clear(): Response
    {
        SessionController::clearOrder();
        return $this->redirectToRoute('show_main_page');
    }

    public function showThankYouPage(): Response
    {
        return $this->render('order/thanks.html.twig');
    }
}