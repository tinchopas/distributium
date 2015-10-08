<?php

namespace Distributium\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\HttpFoundation\JsonResponse;


use Distributium\BackendBundle\Entity\Category;

/**
 * @Route("/category")
 * @Template()
 */
class CategoryController extends Controller
{
    /**
     * @Route("/list")
     * @Template()
     */
    public function listAction()
    {
        return array(
                // ...
	);    
    }

    /**
     * @Route("/show/{id}")
     * @Template()
     */
    public function showAction(Category $category)
    {
        $lodgingTypes = $this->getDoctrine()->getRepository('DistributiumBackendBundle:LodgingType')->findAll();

	    return array(
            'category' => $category,
            'lodgingSizes' => $this->container->getParameter('lodgingSize'),
            'lodgingCategories' => $this->container->getParameter('lodgingCategory'),
            'lodgingTypes' => $lodgingTypes
	    );    
    }

    /**
     * @Route("/showproducts/{category}", options={"expose"=true})
     * @Template()
     */
    public function showProductsAction($category, Request $request)
    {
	    $data = json_decode($request->getContent());

	    $products = $this->getDoctrine()->getManager()->getRepository("DistributiumBackendBundle:Item")
		    ->findByCategory(
			    $category, 
			    $data->lodgingSizes, 
			    $data->lodgingCategories, 
			    $data->lodgingTypes, 
			    $data->regions
		    );
	    $callback = $request->get('callback');
	    $response = new JsonResponse($products, 200, array());
	    $response->setCallback($callback);

	    return $response;
    }

}
