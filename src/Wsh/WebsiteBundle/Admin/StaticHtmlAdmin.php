<?php

namespace Wsh\WebsiteBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class StaticHtmlAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('body', 'translatable_field', array(
                'field' => 'body',
                'widget' => 'ckeditor',
                'property_path' => 'translations',
                'personal_translation' => 'Weniger\GmsBundle\Entity\Translation\StaticHtmlTranslation',
            ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('reference');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('reference');
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('reference')
            ->assertNotNull()
            ->end();
    }

    public function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection->remove('create')->remove('delete');
    }
}
