<?php
namespace AppBundle\Controller\API\QBOAPI;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\QboItems;

class QBOItemController extends FOSRestController
{
    /**
     * @Route("/fetchItems", name="fetchItems")
     * @Method("GET")
     */
    public function fetchItemsAction()
    { 
        try {
            $itemDetails = $this->container->get('data_service')->itemDetails();
        } catch (\Exception $exception) {  
            $this->container->get('data_service')->getNewToken();
            $itemDetails = $this->container->get('data_service')->itemDetails();
        }
        foreach($itemDetails as $key => $value){ 
            $id = $value->Id ?? null;
            $name = $value->Name ?? null;
            $description = $value->Description ?? null;
            $fullQualifiedName = $value->FullyQualifiedName ?? null;
            $incomeAccountRef = $value->IncomeAccountRef ?? null;

            $em = $this->getDoctrine()->getManager();
            $itemDetails = $em->getRepository(QboItems::class)->findOneBy(['itemId' => $id]);
            
            if($itemDetails){
                $itemDetails->setItemId($id);
                $itemDetails->setName($name);
                $itemDetails->setDescription($name);
                $itemDetails->setFullQualifiedName($name);
                $itemDetails->setIncomeAccountRef($name);
                try{
                    $em->persist($itemDetails);
                    $em->flush();
                }catch(\Exception $exception){
                    return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => $exception->getMessage()]);               
                }
            }else{
                $itemObj = new QboItems();
                $itemObj->setItemId($id);
                $itemObj->setName($name);
                $itemObj->setDescription($name);
                $itemObj->setFullQualifiedName($name);
                $itemObj->setIncomeAccountRef($name);
                try{
                    $em->persist($itemObj);
                    $em->flush();
                }catch(\Exception $exception){
                    return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => $exception->getMessage()]);               
                }
            }          
        }
        return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Item List Synced Successfully']);
    }
}