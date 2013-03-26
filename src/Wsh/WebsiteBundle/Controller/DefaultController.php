<?php

namespace Wsh\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function homepageAction()
    {
        return array();
    }

    /**
     * Just a static page rendering action
     * 
     * @Route("/values", name="values")
     * @Template()
     */
    public function valuesAction()
    {
    	return array();
    }

    /**
     * Just a static page rendering action
     *
     * @Route("/services", name="services")
     * @Template()
     */
    public function servicesAction()
    {
    	return array();
    }

    /**
     * Just a static page rendering action
     * 
     * @Route("/clients", name="clients")
     * @Template()
     */
    public function clientsAction()
    {
    	return array();
    }

    /**
     * Just a static page rendering action
     * 
     * @Route("/team", name="team")
     * @Template()
     */
    public function teamAction()
    {
    	return array();
    }

    /**
     * Just a static page rendering action
     * 
     * @Route("/blog", name="blog")
     * @Template()
     */
    public function blogAction()
    {
    	return array();
    }

    /**
     * Just a static page rendering action
     *
     * @Route("/contact", name="contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {

        $defaultData = array();

        // create contact form
        $form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea')
            ->getForm();

        if ($request->isMethod('post')) {
            // user want's to send contact form
            // if not validated pass the data to the defaultData variable
            $form->bind($request);
            if($form->isValid()) {
                $data = $form->getData();

                // sent the form
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact from WenigerSH.com')
                    ->setFrom(array($data['email'] => $data['name']))
                    ->setTo('contact@wenigersh.com')
                    ->setBody(
                        $this->renderView(
                            'WshWebsiteBundle:Default:email.txt.twig',
                            array('message' => $data['message'])
                        )
                    )
                ;
                $this->get('mailer')->send($message);
                $url = $this->get('router')->generate('contact');
                $message = 'Form sent, thank you! <a href="'.$url.'">Send another message</a>. ';
                $status = 1;
                $errors = null;
            } else {
                $errors = $form->getErrorsAsString();
                $message = 'Please fill all required fields';
                $status = -1;
            }
        }

        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse();
            $response->setData(
                array(
                    'status'    => $status,
                    'message'   => $message,
                    'errors'    => $errors
                )
            );
            return $response;
        } else {
            return array(
                'form' => $form->createView(),

            );
        }
    }



    /**
     * Action displays dynamic page content
     *
     * @Route("/{slug}", name="page")
     * @Template()
     */
    public function displayPageAction()
    {
    	return array();
    }
}
