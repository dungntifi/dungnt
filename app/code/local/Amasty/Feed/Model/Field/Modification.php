<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
* @package Amasty_Feed
*/  
class Amasty_Feed_Model_Field_Modification extends Varien_Object
{
    public function modify($val){
        $ret = null;
        
        if (is_numeric($val)){
            $ret = $this->_transform($val);
        } else {
            $ret = $val;
        }
        
        return $ret;
        
    }
    
    protected function _transform($attrValue){
        
        $transform = $this->getValue();
        if (!empty($transform)){
        
            $delta = NULL;

            preg_match("/[0-9]+/", $transform, $matches);
            if ('%' == $transform[strlen($transform)-1] && $matches[0]) {
                $delta = $attrValue*$matches[0]/100;
            } else {
                $delta = $matches[0];
            }

            // transform the attribute value
            switch ($transform[0]) {
                case '+':
                    $attrValue = $attrValue + $delta;
                    break;
                case '-':
                    $attrValue = $attrValue - $delta;
                    break;
                case '*':
                    $attrValue = $attrValue * $delta;
                    break;
                case '/':
                    $attrValue = $attrValue / $delta;
                    break;
            }
        }
        return $attrValue;
    }
}