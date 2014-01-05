<?php

namespace SymEdit\Bundle\CoreBundle\Widget\Strategy;

use SymEdit\Bundle\CoreBundle\Model\PageInterface;
use SymEdit\Bundle\CoreBundle\Model\WidgetInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TemplateStrategy extends AbstractWidgetStrategy
{
    public function execute(WidgetInterface $widget, PageInterface $page = null)
    {
        try {
            $content = $this->render($widget->getOption('template'));
        } catch (\Exception $e) {
            $content = sprintf('There was an error rendering your template: "%s"', $e->getMessage());
        }

        return $content;
    }

    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('template', 'text', array(
                'required' => true,
                'label' => 'Template',
                'constraints' => array(
                    new NotBlank(),
                ),
            ));
    }

    public function getName()
    {
        return 'template';
    }

    public function getDescription()
    {
        return 'Template';
    }
}