<?php
namespace AppBundle\Controller\API\QBOAuth;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\QBOTokens;

class QBOAuthController extends FOSRestController
{
    /**
     * @return array
     * @param Request $request
     * @Get("/oauth/connect", name="getToken")
     */
    function connectAction()
    {
        $connect = true;
        $dataService = $this->container->get('data_service')->prepareDataService($connect);
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        return $this->render('callback.html.twig', ['url' => $authUrl]);
    }

    /**
     * @return array
     * @param Request $request
     * @Get("/callback", name="callback")
     */
    public function callbackAction()
    {
        $connect = true;
        $dataService = $this->container->get('data_service')->prepareDataService($connect);    
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);
    
        //Insert/Update the OAuth2Token
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
        $dataService->updateOAuth2Token($accessToken);
        
        $token = $accessToken->getAccessToken();
        $refreshToken = $accessToken->getRefreshToken();
        $realmId = $accessToken->getRealmID();
        
        $em = $this->getDoctrine()->getManager();
        $tokenDetails = $em->getRepository(QBOTokens::class)->findOneBy(['realmId' => $realmId]);
        if($tokenDetails){
            $tokenDetails->setAccessToken($token);
            $tokenDetails->setRefreshToken($refreshToken);
            $tokenDetails->setRealmId($realmId);
            try {
                $em->persist($tokenDetails);
                $em->flush();
                return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Successfully Updated Tokens']);
            } catch (\Exception $exception) { 
                throw new \Exception($exception->getMessage());
            }
        }else{
            $tokenObj = new QBOTokens();
            $tokenObj->setAccessToken($token);
            $tokenObj->setRefreshToken($refreshToken);
            $tokenObj->setRealmId($realmId);
            try {
                $em->persist($tokenObj);
                $em->flush();
                return new JsonResponse(['code' => Response::HTTP_OK, 'message' => 'Successfully Stored Tokens']);
            } catch (\Exception $exception) { 
                throw new \Exception($exception->getMessage());
            }
        }
    }

    function parseAuthRedirectUrl($url)
    {
        parse_str($url,$qsArray);
        return array(
            'code' => $qsArray['code'],
            'realmId' => $qsArray['realmId']
        );
    }

    
}
