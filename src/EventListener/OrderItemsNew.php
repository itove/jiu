<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @version
 * @todo
 */

namespace App\EventListener;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\OrderItems;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use App\Service\Sn;
use App\Entity\Box;

// #[AsEntityListener(event: Events::prePersist, entity: OrderItems::class)]
#[AsEntityListener(event: Events::postPersist, entity: OrderItems::class)]
class OrderItemsNew extends AbstractController
{
    public function prePersist(OrderItems $item, LifecycleEventArgs $event): void
    {
    }

    public function postPersist(OrderItems $item, LifecycleEventArgs $event): void
    {
        $em = $event->getEntityManager();
        
        $boxes = $item->getBoxes()->toArray();
        $qty = count($boxes);
        $product = $item->getProduct();
        $price = $product->getPrice();
        $amount = $price * $qty;
        
        $ord = $item->getOrd();
        $ord->setAmount($ord->getAmount() + $amount);
        
        $item->setQuantity($qty);
        
        $em->flush();
    }
}
