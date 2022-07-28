<?php

namespace App\Services;

use App\Entity\ShopItem;
use App\Repository\ShopItemRepository;

class ShopItemService
{
    /**
     * @var ShopItemRepository $shopItemRepository
     */
    private $shopItemRepository;

    /**
     * @param ShopItemRepository $shopItemsRepository
     */
    public function __construct(ShopItemRepository $shopItemsRepository)
    {
        $this->shopItemRepository = $shopItemsRepository;
    }

    /**
     * @return ShopItem[]
     */
    public function getAllItems(): array
    {
        return $this->shopItemRepository->findAll();
    }

    public function getItemById(int $id): ShopItem
    {
        return $this->shopItemRepository->find($id);
    }
}