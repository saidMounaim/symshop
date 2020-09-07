<?php 

namespace App\Events;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Order;

class UserEntitySubscriber implements EventSubscriberInterface
{
    protected $security;

    public function __construct( Security $security)
    {
        $this->security = $security;
    }



    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setAuthor', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function setAuthor( ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (($entity instanceof Comment || $entity instanceof Order) && Request::METHOD_POST !== $method) {
            $entity->setUser($this->security->getUser());
        }


    }

}