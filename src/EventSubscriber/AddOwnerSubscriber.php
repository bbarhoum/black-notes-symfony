<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Todo;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AddOwnerSubscriber implements EventSubscriberInterface
{

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addOwner', EventPriorities::PRE_WRITE],
        ];
    }

    public function addOwner(ViewEvent $event): void
    {
        $data = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ((!$data instanceof Todo && !$data instanceof Post && !$data instanceof Comment)
            || Request::METHOD_POST !== $method) {
            return;
        }

        $data->setOwner($this->getUser());
    }

    private function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        return $user instanceof UserInterface ? $user : null;
    }
}