<?php

namespace App\Controller;

use App\Entity\UserRole;
use App\Service\PizzaServiceInterface;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class StorefrontController extends AbstractController
{
    public function __construct(private readonly PizzaServiceInterface $pizzaService,
                                private readonly UserServiceInterface $userService)
    {
    }

    public function index(): Response
    {
        $userId = SessionController::takeIdFromSession();
        $user = $this->userService->getUserById($userId);

        $role = $user->getRoles();
        if (!UserRole::isValidRole($role))
        {
            throw new ForbiddenOverwriteException('Wrong role');
        }

        $pizzas = $this->pizzaService->getListPizzas();
        return $this->render("main_page/main_page.html.twig", [
            "pizzas" => $pizzas,
            "user" => $user
        ]);
    }

    public function order(Request $request): Response
    {
        return $this->redirectToRoute('order', [
            'pizzaId' => $request->get('pizzaId')
        ]);
    }

    public function deletePizza(Request $request): Response
    {
        $id = $request->get('pizzaId');
        $this->pizzaService->deletePizza($id);
        return $this->redirectToRoute('show_main_page');
    }
}
