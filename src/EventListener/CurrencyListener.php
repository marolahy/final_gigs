<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrencyListener implements EventSubscriberInterface
{
    private $defaultCurrency;

    public function __construct($defaultCurrency = 'USD')
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // On essaie de voir si la locale a été fixée dans le paramètre de routing _locale
        if ($locale = $request->attributes->get('_currency')) {
            $request->getSession()->set('_currency', $locale);
        } else {
            // Si aucune locale n'a été fixée explicitement dans la requête, on utilise celle de la session
            $request->setLocale($request->getSession()->get('_currency', $this->defaultCurrency));
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // Doit être enregistré avant le Locale listener par défaut
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}
