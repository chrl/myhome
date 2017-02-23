<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Widget;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="var_dashboard")
     */
    public function indexAction()
    {
        return $this->render('dashboard/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/dashboard/update.json", methods={"GET"}, name="dashboard_update")
     * @return Response
     */
    public function updateAction()
    {
        $widgets = $this->getDoctrine()->getRepository('AppBundle:Widget');

        $widgets = $widgets->findAll();

        $list = [];
        /** @var Widget $widget */
        foreach ($widgets as $widget) {
            $list[$widget->getId()] = [
                'id'=>$widget->getId(),
                'lastchange'=>$widget->getVariable()->getLastupdate(),
                'value'=>$widget->getVariable()->getValue(),
                'type'=>$widget->getType()
            ];
        }
        $response = new Response();
        $response->setContent(json_encode(array(
            'result' =>'ok',
            'widgets'=> $list,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/dashboard/widgets.json", methods={"GET"}, name="dashboard_widgets")
     * @return Response
     */
    public function widgetsAction()
    {
        $widgets = $this->getDoctrine()->getRepository('AppBundle:Widget');

        $widgets = $widgets->findAll();

        $list = [];
        /** @var Widget $widget */
        foreach ($widgets as $widget) {
            $list[$widget->getId()] = [
                'id'=>$widget->getId(),
                'x'=>$widget->getX(),
                'y'=>$widget->getY(),
                'width'=>$widget->getWidth(),
                'height'=>$widget->getHeight(),
                'name'=>$widget->getName(),
                'type'=>$widget->getType(),
                'value'=>$widget->getVariable()->getValue()
            ];

            if ($widget->getType() == 'chart') {
                $list[$widget->getId()]['history'] = $this->get('vars')->getDayHistory($widget->getVariable());
            }
        }
        $response = new Response();
        $response->setContent(json_encode(array(
            'result' =>'ok',
            'widgets'=> $list,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/dashboard/widgets.json", methods={"POST"}, name="dashboard_widgets_save")
     * @param Request $request
     * @return Response
     */
    public function saveWidgetsAction(Request $request)
    {

        $widgets = $request->get('widgets');

        foreach ($widgets as $widget) {
            $w = $this->getDoctrine()->getRepository('AppBundle:Widget')->find($widget['id']);
            if (!$w) {
                continue;
            }

            $w->setWidth($widget['size_x']);
            $w->setHeight($widget['size_y']);
            $w->setX($widget['col']);
            $w->setY($widget['row']);

            $this->getDoctrine()->getManager()->persist($w);
        }

        $this->getDoctrine()->getManager()->flush();

        $response = new Response();
        $response->setContent(json_encode(array(
            'result' =>'ok',
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
