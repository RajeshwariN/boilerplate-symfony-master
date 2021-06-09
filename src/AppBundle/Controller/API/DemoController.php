<?php

/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 04/06/2021
 * Time: 6:46 PM
 */

namespace AppBundle\Controller\API;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends FOSRestController
{
    /**
     * @return array
     * @param Request $request
     * @Get("/home", name="home_get")
     */
    public function homeAction(Request $request)
    {
        try {
            return ['msg' => 'Hello World'];
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }
}
