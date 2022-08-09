<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderFormType;
use App\Services\OrderService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(private OrderService $orderService)
    {
    }

    #[Route('/order', name: 'app_order')]
    public function index(Request $request): Response
    {
        $user = $this->getUser()->getUserIdentifier();
        $order = new Order();
        $form = $this->createForm(OrderFormType::class, $order, ['disabled' => $this->orderService->isEmptyCart($user)]);

        $date = new DateTime();
        $date = $date->getTimestamp();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->orderService->addOrder(
                $user,
                $data->getUserName(),
                $data->getUserEmail(),
                $data->getUserPhone(),
                $date
            );
            // var_dump($data);
            return $this->redirectToRoute('app_order');
        }

        return $this->render('order/index.html.twig', [
            'orderForm' => $form->createView(),
            'form_help' => 'Help',
        ]);
    }

    #[Route('/history', name: 'app_order_history')]
    public function getHistory(): Response
    {
        $orders = $this->orderService->getOrderHistory(
            $this->getUser()->getUserIdentifier()
        );

        return $this->render('order/order_history.html.twig', [
            'title' => 'История заказов',
            'orders' => $orders,
        ]);
    }
}
