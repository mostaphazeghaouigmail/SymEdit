<?php

namespace SymEdit\Bundle\UserBundle;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntitiesPass;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use SymEdit\Bundle\CoreBundle\DependencyInjection\Compiler\DoctrineMappingsPass;
use SymEdit\Bundle\UserBundle\DependencyInjection\SymEditUserExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymEditUserBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        );
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $interfaces = array(
            'SymEdit\Bundle\CoreBundle\Model\UserInterface'    => 'symedit.model.user.class',
            'SymEdit\Bundle\CoreBundle\Model\ProfileInterface' => 'symedit.model.profile.class',
        );

        $container->addCompilerPass(new ResolveDoctrineTargetEntitiesPass('symedit_user', $interfaces));

        /**
         * Add Doctrine Mappings
         */
        DoctrineMappingsPass::addMappings($container, array(
            realpath(__DIR__.'/Resources/config/doctrine/model') => 'SymEdit\Bundle\UserBundle\Model',
        ));
    }

    public function getContainerExtension()
    {
        return new SymEditUserExtension();
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}