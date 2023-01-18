<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @version
 * @todo
 */

namespace App\EventListener;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\DBAL\Exception\DriverException;
use App\Entity\Consumer;
use App\Service\WX;
use App\Service\Poster;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConsumerNew extends AbstractController
{
    private $poster;

    public function __construct(Poster $poster)
    {
        $this->poster = $poster;
    }

    public function postPersist(Consumer $consumer, LifecycleEventArgs $event): void
    {
        $cid = $consumer->getId();
        $this->poster->generate($cid);
    }
}
