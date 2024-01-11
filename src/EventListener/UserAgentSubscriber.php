<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UserAgentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {

    }
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }
    public function onKernelRequest(RequestEvent $event) {
        $this->logger->info($event->getRequest()->headers->get('User-Agent'));
      //  $event->setResponse( new Response('test'));
    }

}