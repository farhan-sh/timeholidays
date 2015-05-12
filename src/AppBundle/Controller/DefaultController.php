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
       $r=array("location"=>$req["location"],"startDate"=>$req["start"],"endDate"=>$req["end"]);
       $query=  "http://www.expedia.com.au/things-to-do/?".http_build_query($r);       
       return $this->render("default/activities.html.twig",array("activity"=>$query));

    }
    /**
     * @route("/admin",name="login")
     */
    public function loginAction(Request $request)
    {
        $session=$request->getSession();
        if($session->get("adminUser")&&$session->get("adminPassword"))
        {
            return $this->render("admin/home.html.twig");
        }
        if($request->getMethod()=="POST" && $request->request->get("submit"))
        {
            if($request->request->get("username")==="admin" && $request->request->get("password")==="admin123")
            {
                $session->set("adminUser",$request->request->get("username"));
                $session->set("adminPassword",$request->request->get("password"));
                return $this->render("admin/home.html.twig");
            }
            else
            {                
                $this->addFlash('invalid', "Your username or password is incorrect");
            }
        }
        return $this->render("admin/login.html.twig");        
    }
    /**
     * @route("/signout",name="signout")
     */
    public function signoutAction(Request $request)
    {
        $session=$request->getSession();
        $session->clear();
        return $this->forward("AppBundle:Default:Login");
        
    }
}
