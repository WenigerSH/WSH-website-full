<?php

namespace Wsh\WebsiteBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Weniger\GmsBundle\Entity\Page;
use Imagine\Gd\Imagine;

class BlogPostAdmin extends Admin
{
    protected $settings;

    protected function configureFormFields(FormMapper $formMapper)
    {
	
		// description for photo (if added)
		$help = "";
        $var = $this->getSubject()->getPath();
		if($this->getSubject()->getPath() != null) {
			$help = 'Adding new photo will overwrite current photo<br><img style="width: 100px;" src="/' . $this->getSubject()->getWebPath() . '">';
		}

        $categories = $this->settings->get('blog.categories');

		$formMapper
            ->with('General')
                ->add('title', null, array(
                ))
                ->add('leadText', null, array(
                    //'widget' => 'textarea',
                    'help'  => 'HTML allowed',
                ))
                ->add('leadPhoto', 'file', array(
					'required' => false,
					'help' => $help
					))
                ->add('body', null, array(
                    //'widget' => 'ckeditor',
                ))
                ->add('quote', null, array(
                    //'widget' => 'textarea',
                    'required' => false,
                    'help'  => 'Optional, HTML not allowed',
                ))
                ->add('isPublished', null, array('required' => false))
                ->add('author', null,  array('required' => true))
                ->add('category', 'choice', array('choices' => $categories))
            ->end()
            ->with('Settings')
            ->add('isFeatured', null, array('help' => 'Featured posts are better exposed on site', 'required' => false))
            // categories are edited in settings.yml
                ->add('metaTitle', null, array('help' => 'If left empty then document title will be used', 'required' => false))
                ->add('metaDescription', null, array('help' => 'If left empty then document body (first 255 characters) will be used', 'required' => false))
                ->add('metaKeywords')
                ->add('slug', null, array('required' => false, 'help' => 'If left blank auto slug from title will be generated'))
            ->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('isPublished')
            ->add('leadText')
            ->add('author')
            ->add('createdAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('leadText')
            ->add('createdAt')
            ->add('isPublished')
            ->add('author')
            ->add('slug');

            // add custom action links
//            ->add('_action', 'actions', array(
//                'actions' => array(
//                    'preview' => array('template' => 'WenigerGmsBundle:Admin:blogPostPreviewListAction.html.twig'),
//                )
//            ));
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
            ->assertNotNull()
            ->end()
            ->with('body')
            ->assertNotNull()
            ->end()
            ->with('leadText')
            ->assertNotNull()
            ->end()
            ->with('author')
            ->assertNotNull()
            ->end();
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
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
