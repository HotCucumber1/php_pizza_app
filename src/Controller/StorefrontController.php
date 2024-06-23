<?php

namespace App\Controller;

use App\Service\PizzaServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class StorefrontController extends AbstractController
{
    public function __construct(private readonly PizzaServiceInterface $pizzaService)
    {
    }

    public function index(): Response
    {
        $pizzas = $this->pizzaService->getListPizzas();
        return $this->render("main_page/main_page.html.twig", [
            "pizzas" => $pizzas
        ]);
    }

    public function order(Request $request): Response
    {
        return $this->redirectToRoute('order', [
            'pizzaId' => $request->get('pizzaId')]);
    }
}
