<?php

namespace App\Services;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\ShopItemRepository;

class CartService
{

    /**
     * @var ShopItemRepository
     */
    private $shopItemRepository;

    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @param ShopItemRepository $shopItemRepository
     * @param CartRepository $cartRepository
     */
    public function __construct(
        ShopItemRepository $shopItemRepository,
        CartRepository $cartRepository
    ) {
        $this->shopItemRepository = $shopItemRepository;
        $this->cartRepository = $cartRepository;

    }


    /**
     * @param  $userId
     * @param int $itemId
     */
    public function AddItemToCart( $userId, int $itemId)
    {
        $item = $this->shopItemRepository->find($itemId);
        $cart = (new Cart())
            ->setUserId($userId)
            ->setItemId($item)
            ->setCount(1);
        $this->cartRepository->add($cart,true);
    }

    /**
     * @param  $userId
     * @param int $itemId
     */
    public function removeItemFromCart( $userId, int $itemId)
    {
        $cart = $this->cartRepository->findOneBy(['user_id' => $userId,'item_id' => $itemId]);
        $this->cartRepository->remove($cart,true);
    }

    /**
     * @param $userId
     * @return array
     */
    public function getCartByUserId($userId): array
    {
        return $this->cartRepository->findBy(['user_id' => $userId, 'order_id' => null]);
    }

    /**
     * @param $userId
     */
//    public function removeCart($userId)
//    {
//        var_dump($this->cartRepository->findBy(['user_id' => $userId]));
//        foreach ($this->getCartByUserId() as $cart)
//        {
//            $this->cartRepository->remove($cart,true);
//        }
//
//    }
}