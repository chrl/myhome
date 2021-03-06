<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NooliteController extends Controller
{

    private $channelMap = [

        1 => ['name'=>'noolite.main', 'source'=>'human'],
        2 => ['name'=>'noolite.kitchen', 'source'=>'human'],
        3 => ['name'=>'noolite.diningroom', 'source'=>'human'],
        4 => ['name'=>'noolite.main', 'source'=>'pi'],
        5 => ['name'=>'noolite.toilet', 'source'=>'pi'],
        6 => ['name'=>'noolite.diningroom', 'source'=>'pi'],
        7 => ['name'=>'noolite.kitchen', 'source'=>'pi'],
        9 => ['name'=>'noolite.toilet', 'source'=>'human,upper'],
        10 => ['name'=>'noolite.toilet', 'source'=>'human,lower'],
    ];

    /**
     * @Route("/noolite", methods={"GET"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function putAction(Request $request)
    {

        $channel = $request->get('ch');

        if (!$channel) {
            return $this->sendResponse(false, ['message'=>'No channel is set']);
        }


        if (isset($this->channelMap[$channel])) {
            $device = $this->
                        getDoctrine()->
                        getRepository('AppBundle:Device')->
                        findOneBy(['alias'=>$this->channelMap[$channel]['name']]);

            if (!$device) {
                return $this->sendResponse(
                    false,
                    ['message'=>'Device "'.$this->channelMap[$channel]['name'].'" not found']
                );
            }

            $actions = $device->getActions();


            $resultAction = null;
            $changeSet = [];


            if ($request->get('cmd') == 4) {
                // reverse state, only by human

                $oldState = $device->getState();
                $oldState = $oldState['state'];

                if ($oldState == 'off') {
                    // turning on
                    $changeSet = ['state'=>'on'];

                    /** @var Action $action */
                    foreach ($actions as $action) {
                        $args = json_decode($action->getArguments(), true);
                        if (isset($args['state']) && ($args['state'] == 'on')) {
                            $resultAction = $action;
                            break;
                        }
                    }
                } else {
                    // turning off
                    $changeSet = ['state'=>'off'];
                    /** @var Action $action */
                    foreach ($actions as $action) {
                        $args = json_decode($action->getArguments(), true);
                        if (isset($args['state']) && ($args['state'] == 'off')) {
                            $resultAction = $action;
                            break;
                        }
                    }
                }
            }

            if ($request->get('cmd') == 0) {
                // turn off, only by pi

                $changeSet = ['state'=>'off'];
                /** @var Action $action */
                foreach ($actions as $action) {
                    $args = json_decode($action->getArguments(), true);
                    if (isset($args['state']) && ($args['state'] == 'off')) {
                        $resultAction = $action;
                        break;
                    }
                }
            }

            if ($request->get('cmd') == 6) {
                // turn off, only by pi

                $percent = round((($request->get('d0') - 34)/123)*100);

                $changeSet = ['state'=>'on', 'percent'=>$percent];
                /** @var Action $action */
                foreach ($actions as $action) {
                    $args = json_decode($action->getArguments(), true);
                    if (isset($args['state']) && ($args['state'] == 'on')) {
                        $resultAction = $action;
                        break;
                    }
                }
            }

            if ($request->get('cmd') == 2) {
                // turn on, only by pi

                $changeSet = ['state'=>'on'];
                /** @var Action $action */
                foreach ($actions as $action) {
                    $args = json_decode($action->getArguments(), true);
                    if (isset($args['state']) && ($args['state'] == 'on')) {
                        $resultAction = $action;
                        break;
                    }
                }
            }

            if (($changeSet['state'] == 'off') && !isset($changeSet['percent'])) {
                $changeSet['percent'] = 0;
            }

            if (($changeSet['state'] == 'on') && !isset($changeSet['percent'])) {
                $changeSet['percent'] = 100; // ? I don't really know if they restore their states
            }

            if (!$resultAction) {
                return $this->sendResponse(true, ['message'=>'State was changed, but no action was found']);
            }

            $this->get('actions')->executeVirtual($resultAction, $this->channelMap[$channel]['source'], $changeSet);

            $this->getDoctrine()->getManager()->flush();

            return $this->sendResponse(
                true,
                [
                    'message'=>'Executed successfully',
                    'device'=>$device->getAlias(),
                    'changeset'=>$changeSet
                ]
            );
        } else {
            return $this->sendResponse(false, ['message'=>'Requested channel does not exist']);
        }
    }

    /**
     * @param $success
     * @param array $resp
     * @return Response
     */
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
