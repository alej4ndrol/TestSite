<?php
namespace App\Services;

use App\Entity\Order;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @param UserRepository $userRepository
     * @param OrderRepository $orderRepository
     * @param CartRepository $cartRepository
     */
    public function __construct(
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        CartRepository $cartRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
    }

    public function isEmptyCart( string $username): bool
    {
        //var_dump(empty($this->cartRepository->findBy(['order_id' => null])));
        return empty($this->cartRepository->findBy(['order_id' => null]));
    }

    /**
     * @param string $username
     * @param string $userName
     * @param string $userEmail
     * @param string $userPhone
     * @param string $date
     * @return void
     */
    public function addOrder(
        string $username,
        string $userName,
        string $userEmail,
        string $userPhone,
        string $date
    )
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        $order = new Order();
        $order->setUser($user);
        $order->setUserName($userName);
        $order->setUserEmail($userEmail);
        $order->setUserPhone($userPhone);
        $order->setDate($date);
        $order->setStatus(Order::STATUS_NEW_ORDER);
        $this->orderRepository->add($order,true);
        $cartArray = $this->cartRepository->findBy(['user_id' => $user, 'order_id' => null]);
        foreach ($cartArray as $cart)
        {
            $this->cartRepository->addOrderId($cart,$order);
            $this->cartRepository->add($this->cartRepository->addOrderId($cart,$order),true);
        }
    }

    public function getOrderHistory(string $username) : array
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        $orders = $this->orderRepository->findBy(['user' => $user]);
        $tmp = [];
        foreach ($orders as $order) {
            //$order->getCarts()
            $items = $this->cartRepository->findBy(['order_id' => $order]);
            $shopItems = [];
            foreach ($items as $item) {
                $shopItems[] = [
                    'name' => $item->getItemId()->getName(),
                    'cost' => $item->getItemId()->getPrice(),
                    'img' => $item->getItemId()->getItemImageSrc(),
                    'count' => $item->getCount(),
                ];
            }
            $tmp[] = [
                'date' => date('d-m-Y',$order->getDate()),
                'items' => $shopItems,
                'id' => $order->getId()
            ];

        }
        return $tmp;
    }
}