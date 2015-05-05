<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    
    /**
     * @route("/activities",name="activities")
     */
    public function activitiesAction(Request $request)
    {
       $req=  ($request->query->all());     
       $r=["location"=>$req["location"],"startDate"=>$req["start"],"endDate"=>$req["end"]];
       $query=  "http://www.expedia.com.au/things-to-do/?".http_build_query($r);       
       return $this->render("default/activities.html.twig",array("activity"=>$query));

    }
}
