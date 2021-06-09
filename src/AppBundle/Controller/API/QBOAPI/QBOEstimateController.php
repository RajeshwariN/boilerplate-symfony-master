<?php
namespace AppBundle\Controller\API\QBOAPI;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class QBOEstimateController extends FOSRestController
{

    /**
     * @Route("/createEstimate", name="createEstimate")
     * @Method("POST")
     */
    public function createEstimateAction(Request $request)
    { 
        try{
            $request = $request->request->all();
            $estimateCreateDetails = $this->container->get('data_service')->createEstimate($request);
        } catch (\Exception $exception) {
            $this->container->get('data_service')->getNewToken();
            $estimateCreateDetails = $this->container->get('data_service')->createEstimate($request); 
        }     
        return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Estimate Created Successfully with ID: '. $estimateCreateDetails->Id]);
    }

    /**
     * @Route("/fetchEstimate", name="fetchEstimate")
     * @Method("GET")
     */
    public function fetchEstimateAction()
    { 
        $data = [];
        try {
            $estimateDetails = $this->container->get('data_service')->estimateDetails();
        } catch (\Exception $exception) {  
            $this->container->get('data_service')->getNewToken();
            $estimateDetails = $this->container->get('data_service')->estimateDetails();
        }
        foreach($estimateDetails as $key => $value){ 
            $data[] = [
                'id' => $value->Id ?? null,
                'CustomerRef' => $value->CustomerRef ?? null,
                'CustomerMemo' => $value->CustomerMemo ?? null,
                'TotalAmt' => $value->TotalAmt ?? null,
                'DocNumber' => $value->DocNumber ?? null,
                'TxnDate' => $value->TxnDate ?? null,
            ];
        }
        return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Estimate List','data' => $data]);
    }
}