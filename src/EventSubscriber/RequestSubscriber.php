<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Psr\Log\LoggerInterface;

class RequestSubscriber implements EventSubscriberInterface
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];  // { ESTE CÓDIGO SE EJECUTA CADA VEZ QUE HAEMOS USO DEL KERNEL DE DE REQUEST }
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // ... { ESTE CÓDIGO SE EJECUTA CADA VEZ QUE HAEMOS USO DEL KERNEL DE DE REQUEST }
        // {podemos usar el logger, con eso demostramos que hemos atrapado el evento y vamos a hacer cosas)
        $request = $event->getRequest();

        // para depurar y probar
        // dd($event);

        $this->logger->info('Probando');

    }
}
