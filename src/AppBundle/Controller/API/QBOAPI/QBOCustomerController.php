<?php
namespace AppBundle\Controller\API\QBOAPI;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\QboCustomers;

class QBOCustomerController extends FOSRestController
{
     /**
     * @Route("/fetchCustomers", name="fetchCustomer")
     * @Method("GET")
     */
    public function fetchCustomerAction()
    { 
        try {
            $customerDetails = $this->container->get('data_service')->customerDetails();
            } catch (\Exception $exception) { 
            $this->container->get('data_service')->getNewToken();
            $customerDetails = $this->container->get('data_service')->customerDetails();
        }

        // Add a customer to DB            
        $em = $this->getDoctrine()->getManager();
        foreach($customerDetails as $key => $customerValue){ 
            $fullName = $customerValue->FullyQualifiedName;
            $companyName = $customerValue->CompanyName ?? null;
            $mobileNo = !empty($customerValue->PrimaryPhone) ? $customerValue->PrimaryPhone->FreeFormNumber : null;
            $email = !empty($customerValue->PrimaryEmailAddr) ? $customerValue->PrimaryEmailAddr->Address : null;

            $custDetails = $em->getRepository(QboCustomers::class)->findOneBy(['emailId' => $email]);
            if($custDetails){
                $custDetails->setFullName($fullName);
                $custDetails->setCompanyName($companyName);
                $custDetails->setEmailId($email);
                $custDetails->setMobileNo($mobileNo);   
                try{ 
                    $em->persist($custDetails);
                    $em->flush();
                }catch (\Exception $exception) {
                    return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => $exception->getMessage()]);               
                }
            }else{
                $customerObj = new QboCustomers();
                $customerObj->setFullName($fullName);
                $customerObj->setCompanyName($companyName);
                $customerObj->setEmailId($email);
                $customerObj->setMobileNo($mobileNo);
                try{
                    $em->persist($customerObj);
                    $em->flush();
                }catch (\Exception $exception) { 
                    return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => $exception->getMessage()]);               
                }
            }
        }
        return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Successfully Synced Customer Data']);
    }     
}
