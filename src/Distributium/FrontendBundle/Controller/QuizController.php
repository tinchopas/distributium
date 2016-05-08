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
        $lodgingTypes = $this->getDoctrine()->getRepository('DistributiumBackendBundle:LodgingType')->findAll();

	    return array(
            'lodgingSizes' => $this->container->getParameter('lodgingSize'),
            'lodgingCategories' => $this->container->getParameter('lodgingCategory'),
            'lodgingRegions' => $this->container->getParameter('lodgingRegion'),
            'lodgingTypes' => $lodgingTypes
	    );    
    }

    /**
     * @Route("/results", options={"expose"=true})
     * @Template()
     */
    public function resultsAction()
    {
        $params = $this->getRequest()->request->all();

        $results = $this->getDoctrine()
            ->getRepository('DistributiumBackendBundle:Item')
            ->searchQuizResults($params);

        //ldd($results);

        /*
        $quizResults = [];
        foreach ($results as $item) {
            $category = $item->getCategory();
            $key = sprintf('%s_%s', $item->getId(), $category->getId());
            $quizResults[$key] = $item->getName();
        }

        ldd($quizResults);
         */

        return array(
            'results' => $results
	    );    
    }
}
