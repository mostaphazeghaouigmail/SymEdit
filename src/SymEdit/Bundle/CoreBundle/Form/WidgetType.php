<?php

namespace SymEdit\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SymEdit\Bundle\CoreBundle\Form\DataTransformer\WidgetAssociationTransformer;
use SymEdit\Bundle\CoreBundle\Model\Widget;

class WidgetType extends AbstractType
{
    protected $widgetClass;
    protected $widgetAreaClass;

    public function __construct($widgetClass, $widgetAreaClass)
    {
        $this->widgetClass = $widgetClass;
        $this->widgetAreaClass = $widgetAreaClass;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new WidgetAssociationTransformer();

        $basic = $builder->create('basic', 'tab', array(
            'label' => 'Basic',
            'icon' => 'info-sign',
            'inherit_data' => true,
            'data_class' => $this->widgetClass,
        ))
            ->add('title')
            ->add('name', 'text', array(
                'help_block' => 'Numbers, letters, underscores, or hyphens.',
            ))
            ->add('area', 'entity', array(
                'property' => 'area',
                'class' => $this->widgetAreaClass,
            ))
            ->add('visibility', 'choice', array(
                'label' => 'Visibility',
                'choices' => array(
                    Widget::INCLUDE_ALL => 'Include on ALL Pages',
                    Widget::INCLUDE_ONLY => 'Include ONLY on specified',
                    Widget::EXCLUDE_ONLY => 'Exclude ONLY on specified',
                ),
            ))
            ->add(
                $builder->create('assoc', 'textarea', array(
                    'label' => 'Associations',
                    'required' => false,
                    'auto_initialize' => false,
                    'attr' => array(
                        'rows' => 8,
                    ),
                ))->addModelTransformer($transformer)
            );

        $builder->add($basic);

        /**
         * Build the config form from the strategy
         */
        $config = $builder->create('options', 'tab', array(
            'label' => 'Options',
            'icon' => 'cog',
        ));

        $options['strategy']->buildForm($config);

        /**
         * Add to the final form if config has children
         */
        if($config->count() > 0){
            $builder->add($config);
        }

        return $builder->getForm();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'tabs_class' => 'nav nav-pills nav-stacked',
        ));

        $resolver->setRequired(array(
            'strategy',
        ));
    }

    public function getName()
    {
        return 'isometriks_symedit_widget';
    }
}