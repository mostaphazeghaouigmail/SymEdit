<?php

namespace Isometriks\Bundle\SymEditBundle\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController; 
use Symfony\Component\HttpFoundation\Response; 

class Controller extends BaseController
{
    /**
     * Creates a new response and only sets it to public if caching is allowed.  
     * 
     * @return \Symfony\Component\HttpFoundation\Response Response Object
     */
    public function createResponse(\DateTime $modified = null)
    {
        $response = new Response(); 
        $response->setVary('Cookie'); 
        
        if($this->isCacheable()){
            
            if($modified === null){
                $modified = new \DateTime(); 
            }
            
            $response->setLastModified($modified); 
            $response->setPublic(); 
            $response->setSharedMaxAge(600);
        }
        
        return $response; 
    }
    
    /**
     * Determines whether the response should be cached or not, checks the
     * settings for the cache setting, and whether or not live editing is allowed.
     * 
     * @return boolean
     */
    public function isCacheable()
    {
        $settings = $this->getSettings(); 
        $context = $this->get('security.context'); 
        
        $cacheable = $settings->has('advanced.caching') && $settings->get('advanced.caching') === 'cache'; 
        $editable = $context->isGranted('ROLE_ADMIN_EDITABLE'); 
        
        return $cacheable && !$editable; 
    }
    
    /**
     * Gets the host bundle
     * 
     * @return string The host bundle
     */
    public function getHostBundle()
    {
        return $this->container->getParameter('isometriks_sym_edit.host_bundle'); 
    }
    
    /**
     * Prepends the host bundle to the template in format HostBundle:Controller:template
     * 
     * @param string $controller
     * @param string $template
     * @return string Template in format HostBundle:Controller:template
     */
    public function getHostTemplate($controller, $template)
    {
        return $this->getHostBundle().':'.$controller.':'.$template; 
    }
    
    /**
     * Gets Settings
     * 
     * @return \Isometriks\Bundle\SettingsBundle\Model\Settings Settings
     */
    public function getSettings()
    {
        return $this->get('isometriks_settings.settings'); 
    }
}