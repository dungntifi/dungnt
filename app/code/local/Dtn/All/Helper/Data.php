<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/14/14
 * Time: 9:23 AM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_All_Helper_Data extends Mage_Core_Helper_Data{

    public function getcfgReview(){
        $stringReview = Mage::getStoreConfig("dtn_all/setting/review");
        $arrayReview = explode(',',$stringReview);
        return $arrayReview;
    }

    public function getSizeChartByDesigner($designer){
        if($designer){
            $html = '';
            $collection = Mage::getModel('amshopby/value')->load($designer, 'option_id');
            $html .= '<div id=\"size-chart-by-country\">';
            $html .= '<div id=\"tabs-size-chart\">';
            $html .= '<label for=\"tab1\">'.$this->__("US").'<input name=\"tab\" type=\"radio\" value=\"us\" /></label>';
            $html .= '<label for=\"tab2\">'.$this->__("UK").'<input name=\"tab\" type=\"radio\" value=\"uk\" /></label>';
            $html .= '<label for=\"tab3\">'.$this->__("EU").'<input name=\"tab\" type=\"radio\" value=\"eu\" /></label>';
            $html .= '</div>';
            $html .= '<div class=\"tab_container\">';
            $html .= '<div id=\"us\" class=\"tab_content\">'.addslashes($collection->getSizeChartUs()).'</div>';
            $html .= '<div id=\"uk\" class=\"tab_content\">'.addslashes($collection->getSizeChartUk()).'</div>';
            $html .= '<div id=\"eu\" class=\"tab_content\">'.addslashes($collection->getSizeChartEu()).'</div>';
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }
    }

    public function getCollectionByOption($option){
        $collection = Mage::getModel('amshopby/value')->getCollection();
        $collection->getSelect()->where("`option_id` = $option");
        foreach($collection as $item){
            return $item->getData();
        }
    }
}