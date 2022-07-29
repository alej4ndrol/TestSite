<?php

namespace App\Controller;

use App\Services\ShopItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopItemController extends AbstractController
{

    /**
     * @var ShopItemService
     */
    private  $shopItemService;

    /**
     * @param ShopItemService $shopItemService
     */
    public function __construct(ShopItemService $shopItemService)
    {
        $this->shopItemService = $shopItemService;
    }

    #[Route('/shop', name: 'app_shop')]
    public function index(): Response
    {
        $items = $this->shopItemService->getAllItems();
        return $this->render('shop_item/index.html.twig', [
            'items' => $items,
            'page' => 'shop'
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/shop/search', name: 'app_search')]
    public function search(Request $request): Response
    {
        $name = $request->query->get('name');
        $items = $this->shopItemService->searchByName($name);
        return $this->render('shop_item/index.html.twig', [
            'items' => $items,
            'page' => 'Search: '. $name
        ]);
    }

    #[Route('/shop/item/{id<\d+>}', name: 'app_shop_item')]
    public function ShopItem(int $id) :Response
    {
        $item = $this->shopItemService->getItemById($id);
        return $this->render('/shop_item/ShopItem.html.twig', [
            'item' => $item
        ]);
    }
}
