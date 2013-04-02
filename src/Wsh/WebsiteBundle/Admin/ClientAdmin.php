<?php

namespace Wsh\WebsiteBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Weniger\GmsBundle\Entity\Page;

class ClientAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        // description for photo (if added)
        $help = "";
        $var = $this->getSubject()->getPath();
        if($this->getSubject()->getPath() != null) {
            $help = 'Adding new photo will overwrite current photo<br><img style="width: 100px;" src="/' . $this->getSubject()->getWebPath() . '">';
        }

        $formMapper
            ->with('General')
                ->add('name', null, array(
                    'label' => 'Full client name',
                ))
                ->add('country', null, array(
                    //'widget' => 'ckeditor',

                ))
                ->add('city', null, array(
                    'label' => 'City of client HQ'
                ))
                ->add('description', 'ckeditor', array(
                    'required' => false,
                ))
                ->add('logo', 'file', array(
                'required' => false,
                'help' => $help
                ))
                ->add('isPublished', null, array(
                    'required' => false,
                    'attr'  => array(
                        'class' => 'published'
                    )
                ))
                ->add('position', null, array(
                    'required' => false,
                ))
                ->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('country')
            ->add('isPublished');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('country')
            ->add('isPublished');

            // add custom action links
//            ->add('_action', 'actions', array(
//                'actions' => array(
//                    //'preview' => array('template' => 'WenigerGmsBundle:Admin:pagePreviewListAction.html.twig'),
//                )
//            ));
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('name')
            ->assertNotNull()
            ->end()
            ->with('country')
            ->assertNotNull()
            ->end()
            ->with('city')
            ->assertNotNull()
            ->end();
    }

    /**
     * todo: upload lead photo here
     * @param mixed $blogPost
     * @return mixed|void
     */
    public function prePersist($blogPost) {
        $blogPost->upload();
    }

    /**
     * todo: upload lead photo here
     *
     * @param mixed $blogPost
     * @return mixed|void
     */
    public function preUpdate($blogPost) {
        $blogPost->upload();
    }

    /**
     * todo: remove photo here
     *
     * @param mixed $blogPost
     * @return mixed|void
     */
    public function preRemove($blogPost) {
        $this->saveFile($blogPost);
    }
}
