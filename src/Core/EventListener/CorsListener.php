<?php

namespace App\Core\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CorsListener
{
    /**
     *
     * check if we have a options CORS request - dont process anything in this case ! - just say its OK
     *
     *
     *  also check https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Preflighted_requests
     *
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($request->headers->has("Access-Control-Request-Headers") && $request->headers->has("Access-Control-Request-Method")) {

            $response = new Response();
            $response->headers->add(
                array('Access-Control-Allow-Headers' => $request->headers->get("Access-Control-Request-Headers"),
                    'Access-Control-Allow-Methods' => $request->headers->get("Access-Control-Request-Method"),
                    'Access-Control-Allow-Origin' => '*'));
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }

    /**
     *
     * add CORS crossdomain for all requests accepting JSON
     *
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {

        $response = $event->getResponse();
        $request = $event->getRequest();
        if ($request->headers->has("Accept") && strstr($request->headers->get("Accept"), "application/json")) {
            $response->headers->add(array('Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, X-Token, Authorization, X-Bearer-Token',
                'Access-Control-Expose-Headers' => '*',
                'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, PATCH, OPTIONS'));
        }
    }
}