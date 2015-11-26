<?php

namespace Distributium\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/quiz")
 * @Template()
 */
class QuizController extends Controller
{
    /**
     * @Route("/home", options={"expose"=true})
     * @Template()
     */
    public function homeAction()
    {
	    return array(
	    );    
    }
}
