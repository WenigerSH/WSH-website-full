<?php

namespace Wsh\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wsh\WebsiteBundle\Entity;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function homepageAction()
    {
        // fetch 10 latest blog posts
        $pageRepo = $this->getDoctrine()->getRepository('Wsh\WebsiteBundle\Entity\BlogPost');
        $blogPosts = $pageRepo->fetchLatestPublishedBlogPosts(10);
        return array(
            'blogPosts' => $blogPosts
        );
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
     * View simple dynamic text page
     *
     * @Template()
     * The Route part has been removed so that proper route sequence can be set in routing.yml
     * @param $slug page slug to view
     */
    public function viewPageAction($slug)
    {
        $page =  $this->getDoctrine()->getRepository('Wsh\WebsiteBundle\Entity\Page')->findOneBySlug($slug);
        if (!$page) {
            throw $this->createNotFoundException('Page with slug: "'.$slug.'" not found in database');
        }
        if (!$page->isPublished()) {
            throw $this->createNotFoundException('Given page is not published');
        }

        // pass if logged user is admin
        $user = $this->getUser();
        $admin = false;
        if(is_object($user)) {
            if($user->getType() == 'Admin') {
                $admin = true;
            }
        }

        return array(
            'document' => $page,
            'admin' => $admin
        );
    }

    /**
     * View given blog post by slug
     *
     * @param $slug string
     * @Template()
     * @Route("/blog/post/{slug}", name="blog_single", requirements={"slug": "\D.*"})
     * @throws /NotFoundException when not published or not found
     * @return array
     */
    public function viewBlogPostAction($slug)
    {
        $repo       = $this->getDoctrine()->getRepository('Wsh\WebsiteBundle\Entity\BlogPost');
        $document   = $repo->findOneBySlug($slug);
        if (empty($document)) {
            throw $this->createNotFoundException('Blog post with slug: '.$slug.' not found');
        }

        if (!$document->isPublished()) {
            throw $this->createNotFoundException('Given blog post is not published');
        }
        $selectedCategory = $document->getCategory();
        // fetch next and previous blog posts
        $next = $repo->getNextBlogPostFor($document);
        $previous = $repo->getPreviousBlogPostFor($document);
        //var_dump($previous);
        // pass if logged user is admin
        $user = $this->getUser();
        $admin = false;
        if(is_object($user)) {
            if($user->getType() == 'Admin') {
                $admin = true;
            }
        }
        return compact('document', 'next', 'previous', 'selectedCategory', 'admin');
    }

    /**
     * View blog post listing
     *
     * @Route("/editorial/{category}/{page}", name="blog_all", defaults={"page": 1, "category": "all"}, requirements={"page": "\d+"})
     * @Template()
     */
    public function viewAllBlogPostsAction($category, $page, Request $request)
    {
        // make query for pagination
        //$dql = "SELECT n FROM WenigerGmsBundle:BlogPost n WHERE n.isPublished = true ORDER BY n.createdAt DESC";
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n')
            ->from('WshWebsiteBundle:BlogPost', 'n')
            ->where('n.isPublished = true')
            ->orderBy('n.createdAt', 'DESC')
            ->addOrderBy('n.id', 'DESC');

        if ($category != 'all' and !is_numeric($category) and $category != 'search') { // if not a year and not search
            $qb->andWhere('n.category = :category');
            $qb->setParameter('category', $category);
        }

//        if ($category == 'search') {
//            $searchString = $request->query->get('editorialSearch');
//            $qb->add('where', $qb->expr()->orx(
//                $qb->expr()->like('n.title', $qb->expr()->literal('%'.$searchString.'%')),
//                $qb->expr()->like('n.quote', $qb->expr()->literal('%'.$searchString.'%')),
//                $qb->expr()->like('n.author', $qb->expr()->literal('%'.$searchString.'%'))
//            ));
//        }

//        if ($category != 'all' and is_numeric($category)) {
//            $qb->andWhere($qb->expr()->like('n.createdAt', $qb->expr()->literal($category.'%')));
//
//        }
        $query = $qb->getQuery();

        // get settings for manager for determining pagination settings
        $settings = $this->get('settings');
        $recordsPerPage = $settings->get('blog.pagination.limitPerPage');
        // not count the first result number (remembering it starts from 0)
        $firstResult = ($page * $recordsPerPage) - $recordsPerPage;

        // pagination initialization
        $paginator = $this->get('knp_paginator');

        // make paginated results
        $pagination = $paginator->paginate(
            $query,
            $page,
            $recordsPerPage
        );
        $recordsTotal = $pagination->getTotalItemCount();
        $recordsLoaded = $firstResult + $recordsPerPage;

        // pass selected category to view, change name to avoid colision
        $selectedCategory = $category;

        // return values depending on if it's ajax req.(for autoload) or not
        if ($this->getRequest()->isXmlHttpRequest()) {
            $isAjax = true;
            return $this->render(
                'WenigerGmsBundle:Default:_blogPostsItems.html.twig',
                compact('pagination', 'recordsLoaded', 'recordsTotal', 'isAjax')
            );
        } else {
            $isAjax = false;
            // usual request will use the Template() annotation for rendering
            return compact(
                'pagination',
                'recordsLoaded',
                'recordsTotal',
                'firstPost',
                'isAjax',
                'categories',
                'latest',
                'yearSpanArray',
                'minYear',
                'maxYear',
                'selectedCategory'
            );
        }
    }

    /**
     * Renders template (return) blog sirebar with categories, archive and 5 latests entries
     *
     * @Route("/render/editorialSidebar/{selectedCategory}/{post}", name="blog_render_sidebar", defaults={"selectedCategory" = null, "post" = null})
     */
    public function renderBlogSideBarAction($selectedCategory = null, $post = null)
    {

        $em = $this->getDoctrine()->getManager();
        if ($post !== null) {
            $post = $em->getRepository('WshWebsiteBundle:BlogPost')->find($post);
        }
        $settings = $this->get('settings');

        // get all categories for filtering
        $categories = $settings->get('blog.categories');

        $categories = $this->get('settings')->get('blog.categories');

        // get latest 5 entries
        $latest = $this->getDoctrine()->getRepository('WshWebsiteBundle:Page')->fetchLatestPublishedBlogPosts(8);


        return $this->render(
            'WshWebsiteBundle:Default:_blogSideBar.html.twig',
            compact('categories', 'latest', 'selectedCategory', 'post')
        );

    }
}
