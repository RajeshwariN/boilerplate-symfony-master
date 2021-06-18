<?php
namespace AppBundle\Controller\API\QBOAPI;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\QboEmployees;

class QBOEmployeeController extends FOSRestController
{
    /**
     * @Route("/fetchEmployees", name="fetchEmployee")
     * @Method("GET")
     */
    public function fetchEmployeeAction()
    { 
        try {
            $employeeDetails = $this->container->get('data_service')->employeeDetails();
            } catch (\Exception $exception) { 
            $this->container->get('data_service')->getNewToken();
            $employeeDetails = $this->container->get('data_service')->employeeDetails();
        }
        foreach($employeeDetails as $key => $value){
            $id = $value->Id ?? null;
            $EmployeeType = $value->EmployeeType ?? null;
            $EmployeeNumber = $value->EmployeeNumber ?? null;
            $HiredDate = !empty($value->HiredDate) ? new \DateTime($value->HiredDate)  : null;
            $DisplayName = $value->DisplayName ?? null;
            $PrimaryPhone = $value->PrimaryPhone->FreeFormNumber ?? null;

            $em = $this->getDoctrine()->getManager();
            $empDetails = $em->getRepository(QboEmployees::class)->findOneBy(['empId' => $id]);
            if($empDetails){
                $empDetails->setEmpId($id);
                $empDetails->setEmployeeType($EmployeeType);
                $empDetails->setEmployeeNumber($EmployeeNumber);
                $empDetails->setHiredDate($HiredDate);
                $empDetails->setDisplayName($DisplayName);
                $empDetails->setPhone($PrimaryPhone);
                try{
                    $em->persist($empDetails);
                    $em->flush();
                }catch(\Exception $exception){
                    return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => $exception->getMessage()]);               
                }
            }else{
                $empObj = new QboEmployees();
                $empObj->setEmpId($id);
                $empObj->setEmployeeType($EmployeeType);
                $empObj->setEmployeeNumber($EmployeeNumber);
                $empObj->setHiredDate($HiredDate);
                $empObj->setDisplayName($DisplayName);
                $empObj->setPhone($PrimaryPhone);
                try{
                    $em->persist($empObj);
                    $em->flush();
                }catch(\Exception $exception){
                    return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => $exception->getMessage()]);               
                }
            }
        }
        return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Employee Details Fetched Successfully']);
    
    }
}