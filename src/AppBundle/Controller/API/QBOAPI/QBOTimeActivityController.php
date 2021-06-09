<?php
namespace AppBundle\Controller\API\QBOAPI;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class QBOTimeActivityController extends FOSRestController
{
    /**
     * @Route("/createTimeActivity", name="createTimeActivity")
     * @Method("POST")
     */
    public function createTimeActivityAction(Request $request)
    { 
        try{
            $request = $request->request->all();
            $timeActivityDetails = $this->container->get('data_service')->createTimeActivity($request);
        } catch (\Exception $exception) {
            $this->container->get('data_service')->getNewToken();
            $timeActivityDetails = $this->container->get('data_service')->createTimeActivity($request); 
        }     
        return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Time Activity Created Successfully with ID: '. $timeActivityDetails->Id]);
    }
}