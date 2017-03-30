<?php
namespace Payum\Silex;

use Payum\Core\Request\Notify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotifyController extends PayumController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function doAction(Request $request)
    {
        \Adora\Wordpress\Application::getInstance()['logger']->notice(__METHOD__, array(
            'uri' => $request->getUri(),
            'method' => $request->getMethod(),
            'data' => $request->isMethod('POST') ? $request->request->all() : $request->query->all()
        ));

        $token = $this->payum->getHttpRequestVerifier()->verify($request);

        $gateway = $this->payum->getGateway($token->getGatewayName());

        $gateway->execute(new Notify($token));

        return new Response('', 204);
    }
}
