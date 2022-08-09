<?php

namespace App\Controller;

use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/cartin', name: 'app_cart')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_open');
    }

    /**
     * @param $id
     */
    #[Route('/cartAdd/{id<\d+>}', name: 'app_cart_add')]
    public function cartAdd($id): Response
    {
        $userId = $this->getUser();
        $this->cartService->AddItemToCart($userId, $id);

        return $this->redirectToRoute('app_open', [
            'id' => $id,
        ]);
    }

    /**
     * @param $id
     */
    #[Route('/cartRemove/{id<\d+>}', name: 'app_remove_cart')]
    public function removeItemCart($id): Response
    {
        $userId = $this->getUser();
        $this->cartService->RemoveItemFromCart($userId, $id);

        return $this->redirectToRoute('app_open', [
            // 'id' => $id
        ]);
    }

    /**
     * @Route("/cart", name="app_open")
     */
    public function openCart(): Response
    {
        $userId = $this->getUser();

        return $this->render('/cart/index.html.twig', [
            'title' => 'Cart',
            'items' => $this->cartService->getCartByUserId($userId),
        ]);
    }

//    /**
//     * @return Response
//     */
//    #[Route(name: 'app_clear_cart')]
//    public function clearCart():Response
//    {
//
//        $userId = $this->getUser();
//       $this->cartService->removeCart($userId);
//        return $this->redirectToRoute('app_open');
//    }
}
