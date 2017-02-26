<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Variable;
use AppBundle\Variable\Service;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Variable\Parser;

class NooliteController extends Controller
{


    /**
     * @Route("/noolite", methods={"GET"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function putAction(Request $request)
    {
        return $this->sendResponse(true, ['request'=>$request->getQueryString()]);
    }

    public function sendResponse($success, array $resp)
    {
        $response = new Response();
        $response->setContent(json_encode(array(
            'result' => $success ? 'ok' : 'fail',
            'response'=> $resp,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
