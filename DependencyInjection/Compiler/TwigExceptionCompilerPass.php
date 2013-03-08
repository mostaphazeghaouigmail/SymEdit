<?php

namespace Isometriks\Bundle\SymEditBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigExceptionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Get the exception class
        $controller = $container->getParameter('isometriks_sym_edit.twig.exception.class'); 
        
        // Change the definition to use our class
        $definition = $container->getDefinition('twig.controller.exception'); 
        $definition->setClass($controller);  
    }
}