<?php

namespace Distributium\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Distributium\BackendBundle\Entity\Item;

/**
 * @Route("/product")
 * @Template()
 */
class ProductController extends Controller
{
//, name="productShow"
    /**
     * @Route("/show/{id}", options={"expose"=true})
     * @Template()
     */
    public function showAction(Item $item)
    {
	    return array(
            'item' => $item
	    );    
    }
}
