<?php

namespace App\Services;

use App\Entity\ShopItem;
use App\Repository\ShopItemRepository;

class ShopItemService
{
    /**
     * @var ShopItemRepository
     */
    private $shopItemRepository;

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

    public function searchByName(string $name): array
    {
        return $this->shopItemRepository->findBy(['name' => "$name"]);
    }
}
