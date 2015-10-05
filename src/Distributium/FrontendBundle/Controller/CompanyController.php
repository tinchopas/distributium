<?php

namespace Distributium\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CompanyController extends Controller
{
    /**
     * @Route("/company")
     * @Template()
     */
    public function indexAction()
    {
        return array(
                // ...
            );    }

}
