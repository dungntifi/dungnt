<?php
/**
 * Created by PhpStorm.
 * User: Shandy
 * Date: 10.01.14
 * Time: 21:23
 */
class OpsWay_Varnishgento_Model_Source_TypesTag
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        //try {
        //    throw new Exception('bla');
        //} catch (Exception $e){
        //    print_r($e->getTraceAsString());
        //}
        //return unserialize(Mage::getStoreConfig('opsway_varnishgento/flushing/period_by_tags'));
        return array(
            0 => array('type' => 'PR', 'period'=>Mage::helper('adminhtml')->__('Version 2.x or less')),
            1 => array('type' => 3, 'period'=>Mage::helper('adminhtml')->__('Version 3.0 or greater')),
        );
    }
}