<?php

namespace App\Controller;

use App\Service\PizzaServiceInterface;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddPizzaController extends AbstractController
{
    public function __construct(private readonly PizzaServiceInterface $pizzaService,
                                private readonly UserServiceInterface $userService)
    {
    }

    public function index(): Response
    {
        $id = SessionController::takeUserIdFromSession();
        $user = $this->userService->getUserById($id);
        return $this->render('add_pizza/add_pizza.html.twig', [
            'user' => $user
        ]);
    }

    public function add(Request $request): Response
    {
        $pizzaName = $request->get('name');

        $definition = $request->get('definition');
        $weight = $request->get('weight');
        $price = $request->get('price');
        $image = $request->files->get('image');
        // $type = $request->get('type');



        if ($pizzaName === '' || $definition === '' || $weight === '' || $price === '' || !$image)
        {
            throw new BadRequestException("All fields must be filled in");
        }

        $this->pizzaService->addPizza($pizzaName,
                                      $definition,
                                      $weight,
                                      $price,
                                      $image);
        return $this->redirectToRoute('show_main_page');
    }
}