<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @version
 * @todo
 */

namespace App\EventListener;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Orders;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class OrdersNew extends AbstractController
{
    public function prePersist(Orders $order, LifecycleEventArgs $event): void
    {
        $amount = 0;
        $voucher = 0;

        foreach ($order->getOrderItems() as $i) {
            $product = $i->getProduct();
            $quantity = $i->getQuantity();
            $price = $product->getPrice();
            $unitVoucher = $product->getVoucher();
            // accumulate voucher
            $amount += $price * $quantity;
            // accumulate amount
            $voucher += $unitVoucher * $quantity;
        }

        $order->setAmount($amount);
        $order->setVoucher($voucher);
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
    }
}