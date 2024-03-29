<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @version
 * @todo
 */

namespace App\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Org;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, entity: Org::class)]
class OrgUpdate extends AbstractController
{
    public function postUpdate(Org $org, LifecycleEventArgs $event): void
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($org);

        if (isset($changeSet['admin'])) {
            $ex = $changeSet['admin'][0];
            if (! is_null($ex)) {
                $ex->setReloginRequired(true);
            }
            $admin = $changeSet['admin'][1];
            if (! is_null($admin)) {
                $admin->setReloginRequired(true);
                $admin->setOrg($org);
            }
            $em->flush();
        }
        
        if (isset($changeSet['salesman'])) {
            $salesman = $changeSet['salesman'][1];
            if (! is_null($salesman)) {
                $salesman->addRole('salesman');
            }
            $em->flush();
        }
    }
}
