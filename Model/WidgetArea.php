<?php

namespace Isometriks\Bundle\SymEditBundle\Model; 

use Doctrine\Common\Collections\ArrayCollection; 
use Isometriks\Bundle\SymEditBundle\Model\PageInterface; 

abstract class WidgetArea implements WidgetAreaInterface
{
    protected $id; 
    protected $area; 
    protected $description; 
    protected $widgets; 
    
    public function __construct()
    {
        $this->widgets = new ArrayCollection(); 
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getArea()
    {
        return $this->area;
    }
    
    public function setArea($area)
    {
        $this->area = $area; 
        
        return $this; 
    }
    
    public function setDescription($description)
    {
        $this->description = $description; 
        
        return $this; 
    }
    
    public function getDescription()
    {
        return $this->description; 
    }
    
    public function getWidgets()
    {
        return $this->widgets;
    }
    
    public function addWidget(WidgetInterface $widget)
    {
        $widget->setArea($this); 
        $this->widgets->add($widget);
        
        return $this; 
    }
    
    public function removeWidget(WidgetInterface $widget)
    {
        $this->widgets->removeElement($widget);
        
        return $this; 
    }
    
    public function setWidgets(ArrayCollection $widgets)
    {
        foreach($widgets as $widget){
            $widget->setArea($this); 
        }
        
        $this->widgets = $widgets; 
        
        return $this;
    }
}