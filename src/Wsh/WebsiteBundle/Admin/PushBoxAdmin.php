<?php

namespace Wsh\WebsiteBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class PushBoxAdmin extends Admin
{
    protected $settings;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'translatable_field', array(
                'field' => 'title',
                'personal_translation' => 'Weniger\GmsBundle\Entity\Translation\PushBoxTranslation',
            ))
            ->add('body', 'translatable_field', array(
                'field' => 'body',
                //'widget' => 'ckeditor',
                'personal_translation' => 'Weniger\GmsBundle\Entity\Translation\PushBoxTranslation',
            ))
            ->add('isPublished', null, array(
            'required' => false
            ))
            ->add(
                'zone',
                'choice',
                array(
                    'choices' => $this->settings->get('pushboxes.zones'),
                    'help_label' => 'where this pushbox should appear'
                )
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('isPublished')
            ->add('zone');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('zone')
            ->add('modifiedAt')
            ->add('clicks')
            ->add('views')
            ->add('isPublished');
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
            ->assertNotNull()
            ->end()
            ->with('createdAt')
            ->assertNotNull()
            ->end();
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
