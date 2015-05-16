<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $conn = $this->get('database_connection');
        $media=$conn->prepare("select * from home_page_setting where active=1");
        $media->execute();
        $data=$media->fetchAll();
        return $this->render('default/index.html.twig',array("data"=>$data));
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
            return $this->render("admin/dashboard.html.twig");
        }
        if($request->getMethod()=="POST" && $request->request->get("submit"))
        {
            if($request->request->get("username")==="admin" && $request->request->get("password")==="admin123")
            {
                $session->set("adminUser",$request->request->get("username"));
                $session->set("adminPassword",$request->request->get("password"));
                return $this->render("admin/dashboard.html.twig");
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
    /**
     * @route("/dashboard",name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $session=$request->getSession();
        if($session->get("adminUser")&&$session->get("adminPassword"))
        {
            return $this->render("admin/dashboard.html.twig");
        }
        else
        {
            return $this->forward("AppBundle:Default:Login");
        }
    }
    /**
     * @route("/hpsetting",name="hpsetting")
     */
    public function hpsettingAction(Request $request)
    {
        $conn = $this->get('database_connection');  
        $session=$request->getSession();
        if($session->get("adminUser")&&$session->get("adminPassword"))
        {
            $data=$request->request->all();           
            if($data)
            {     
                
                if($data["hmedia"]==1)
                {
                        $file=new Assert\Image();
                        $file->mimeTypesMessage="Image File Type not Allowed ";                         
                        $errorList = $this->get('validator')->validate(
                        $_FILES['image']["tmp_name"],
                        $file
                     );
                    if (0 === count($errorList))
                    {                                              
                        $hps = $conn->prepare("update home_page_setting set active=0");
                        $hps->execute();                                                
                        $hps = $conn->prepare("update home_page_setting set file='".$_FILES['image']['name']."',active=1 where type=0");
                        $hps->execute();                                                
                        move_uploaded_file($_FILES['image']['tmp_name'], $this->get("kernel")->getRootDir()."/../bundles/app/media/".$_FILES['image']['name']);
                        $this->addFlash('ferror', "Image Added and Shown on Home Page Successfully");
                    } 
                    else
                    {                         
                        $this->addFlash('ferror', $errorList[0]->getMessage());

                    }
                }   
                if($data["hmedia"]==0)
                {
                        $file=new Assert\File(array("mimeTypes"=>array("video/mp4","video/ogg","video/3gpp")));
                        $file->mimeTypesMessage="Media File Type Not Allowed";
                        $errorList = $this->get('validator')->validate(
                        $_FILES['video']["tmp_name"],
                        $file
                     );                        
                    if (0 === count($errorList))
                    {                       
                        $hps = $conn->prepare("update home_page_setting set active=0");
                        $hps->execute();                                                
                        $hps = $conn->prepare("update home_page_setting set file='".$_FILES['video']['name']."',active=1 where type=1");
                        $hps->execute();                                                
                        move_uploaded_file($_FILES['video']['tmp_name'], $this->get("kernel")->getRootDir()."/../bundles/app/media/".$_FILES['video']['name']);
                        $this->addFlash('ferror', "Video Added and Shown on Home Page Successfully");

                    } 
                    else
                    {                
                        $this->addFlash('ferror', $errorList[0]->getMessage());

                    }
                }
            }           
            return $this->render("admin/hpsetting.html.twig");
        }
        else
        {
            return $this->forward("AppBundle:Default:Login");
        }
    }
    /**
     * @route("/about",name="about")
     */
    public function aboutAction(Request $request)
    {
       return $this->render("default/about.html.twig"); 
    }
    /**
     * @route("/terms",name="terms")
     */
    public function termsAction(Request $request)
    {
        return $this->render("default/terms.html.twig");
    }
    /**
     * @route("/privacy",name="privacy")
     */
    public function privacyAction(Request $request)
    {
        return $this->render("default/privacy.html.twig");
    }
    /**
     * @route("/paymethods",name="paymethods")
     */
    public function paymethodsAction(Request $request)
    {
        return $this->render("default/payment.html.twig");
    }
    /**
     * @route("/contact",name="contact")
     */
    public function contactAction(Request $request)
    {
        return $this->render("default/contact.html.twig");
    }
}
