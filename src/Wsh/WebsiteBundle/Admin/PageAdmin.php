<?php

namespace Wsh\WebsiteBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Weniger\GmsBundle\Entity\Page;

class PageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title', null, array(
                    'label' => 'Give yourself some title!',
                ))
                ->add('body', null, array(
                    //'widget' => 'ckeditor',
                ))
                ->add('isPublished', null, array(
                    'required' => false,
                    'attr'  => array(
                        'class' => 'published'
                    )
                ))
            ->end()
                ->with('Meta data')
                ->add('metaTitle', null, array('help' => 'If left empty then document title will be used', 'required' => false))
                ->add('metaDescription', null, array('help' => 'If left empty then document body (first 255 characters) will be used', 'required' => false))
                ->add('metaKeywords')
                ->add('slug', null, array('help' => 'If left blank auto slug from title will be generated', 'required' => false))
            ->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('isSystemic')
            ->add('isPublished')
            ->add('createdAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('createdAt')
            ->add('isPublished')
            ->add('isSystemic')
            ->add('slug');

            // add custom action links
//            ->add('_action', 'actions', array(
//                'actions' => array(
//                    //'preview' => array('template' => 'WenigerGmsBundle:Admin:pagePreviewListAction.html.twig'),
//                )
//            ));
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        /*$errorElement
            ->with('title')
            ->assertNotNull()
            ->end()
            ->with('body')
            ->assertNotNull()
            ->end();
        */
    }
}
