<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Estimate;
use QuickBooksOnline\API\Facades\TimeActivity;

use AppBundle\Entity\QBOTokens;

class QuickBooksService
{
    public $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function prepareDataService($connect = false){
        $authMode = $this->container->getParameter('auth_mode');
        $ClientID = $this->container->getParameter('client_id');
        $ClientSecret = $this->container->getParameter('client_secret');
        $realmId = $this->container->getParameter('realm_id');
        $baseUrl = $this->container->getParameter('base_url');
        $redirectURI = $this->container->getParameter('redirect_URI');
        $scope = $this->container->getParameter('scope');       

        $em = $this->container->get('doctrine')->getManager();
        $tokenDetails = $em->getRepository(QBOTokens::class)->findOneBy(['realmId' => $realmId]);
               
        $dataServiceArr = [
            'auth_mode'       => $authMode,
            'ClientID'        => $ClientID,
            'ClientSecret'    => $ClientSecret,
            'QBORealmID'      => $realmId,
            'baseUrl'         => $baseUrl,
            'RedirectURI'     => $redirectURI,
            'scope'           => $scope
        ];

        if($tokenDetails){
            $accessToken = $tokenDetails->getAccessToken();
            $refreshToken = $tokenDetails->getRefreshToken();
            $dataServiceArr['accessTokenKey'] = $accessToken;
        }
        if(!$connect)
            $dataServiceArr['refreshTokenKey'] = $refreshToken;

        $dataService = DataService::Configure($dataServiceArr);
        return $dataService;
    }

    public function getNewToken()
    {
        try{
            $dataService = $this->prepareDataService(); 

            /*
            * Update the OAuth2Token of the dataService object
            */
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper(); 
            $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken(); 
            $dataService->updateOAuth2Token($refreshedAccessTokenObj);

            $accessToken = $refreshedAccessTokenObj->getAccessToken();
            $realmId = $refreshedAccessTokenObj->getRealmId();
          
            $em = $this->container->get('doctrine')->getManager();
            $tokenDetails = $em->getRepository(QBOTokens::class)->findOneBy(['realmId' => $realmId]);
            $refreshToken = $tokenDetails->getRefreshToken();

            if(!empty($tokenDetails)){
                $tokenDetails->setAccessToken($accessToken);
                $tokenDetails->setRefreshToken($refreshToken);
                try{
                    $em->persist($tokenDetails);
                    $em->flush();
                }catch (\Exception $exception) { 
                   throw new \Exception($exception->getMessage());
                }
            }else{
                $tokenObj = new QBOTokens();
                $tokenObj->setAccessToken($accessToken);
                $tokenObj->setRefreshToken($refreshToken);
                try{
                    $em->persist($tokenObj);
                    $em->flush();
                }catch (\Exception $exception) { 
                    throw new \Exception($exception->getMessage());
                }
            }
        }catch(\Exception $exception){ 
            throw new \Exception($exception->getMessage());
        }
    }

    public function customerDetails(){
        $dataService = $this->prepareDataService();
        $dataService->throwExceptionOnError(true);
        $customerDetails = $dataService->Query("Select * from Customer startposition 1 maxresults 1000");
        return $customerDetails;
    }

    public function estimateDetails()
    {
        $dataService = $this->prepareDataService();
        $dataService->throwExceptionOnError(true);
        $estimateDetails = $dataService->FindAll('estimate');
        //$dataService->Query("Select * from estimate startposition 1 maxresults 1000");
        return $estimateDetails;
    }

    public function itemDetails()
    {
        $dataService = $this->prepareDataService();
        $dataService->throwExceptionOnError(true);
        $itemDetails = $dataService->Query("Select * from Item startposition 1 maxresults 1000");
        return $itemDetails;
    }

    public function employeeDetails()
    {
        $dataService = $this->prepareDataService();
        $employeeDetails = $dataService->Query("Select * from Employee startposition 1 maxresults 1000");
        return $employeeDetails;
    }

    public function createEstimate($request)
    {
        $dataService = $this->prepareDataService();
        $dataService->throwExceptionOnError(true);
        $estimateObj = Estimate::create($request);
        $resultingObj = $dataService->Add($estimateObj);
        return $resultingObj;
    }

    public function createTimeActivity($request)
    {
        $dataService = $this->prepareDataService();
        $dataService->throwExceptionOnError(true);
        $timeActivityObj = TimeActivity::create($request);
        $resultingObj = $dataService->Add($timeActivityObj);
        return $resultingObj;
    }
}