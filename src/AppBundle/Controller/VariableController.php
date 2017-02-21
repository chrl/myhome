<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Variable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Variable\Parser;

class VariableController extends Controller
{

    /**
     * @Route("/var", name="var_index")
     */
    public function indexAction()
    {
        $vars = $this->getDoctrine()->getRepository('AppBundle:Variable');

        $vars = $vars->findAll();

        $list = [];
        /** @var Variable $var */
        foreach ($vars as $var) {
            $list[$var->getName()] = $var->getValue();
        }

        return $this->sendResponse(true, $list);
    }

    /**
     * @Route("/var/{name}", methods={"GET"})
     * @param $name
     * @return Response
     */

    public function getAction($name)
    {
        $vars = $this->getDoctrine()->getRepository('AppBundle:Variable');

        /** @var Variable $var */
        $var = $vars->findOneBy(['name'=>$name]);

        if (!$var) {
            return $this->sendResponse(false,
                [
                    'message'=>'Variable not found',
                ]);
        }

        return $this->sendResponse(true,
                [
                    'name'=>$var->getName(),
                    'value'=>$var->getValue(),
                ]);
    }

    /**
     * @Route("/set/{name}", methods={"GET"})
     * @param Request $request
     * @param $name
     * @return Response
     * @throws \Exception
     */
    public function putAction(Request $request, $name)
    {

        $varService = $this->get('vars');

        $value = $varService->set($name, $request);

        if (!$value) {
            return $this->sendResponse(true,
                [
                    'message'=>'Cannot set variable, maybe value param is missing?',
                ]);
        }

        return $this->sendResponse(true,
            [
                'message'=>'Set: '.$value,
            ]);
    }

    public function sendResponse($success, array $resp)
    {
        $response = new Response();
        $response->setContent(json_encode(array(
            'result' => $success ? 'ok': 'fail',
            'response'=> $resp,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
