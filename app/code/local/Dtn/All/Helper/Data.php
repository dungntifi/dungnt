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
            $html .= '<div id="size-chart-by-country">';
            $html .= '<ul class="tabs">';
            $html .= '<li id="tab-1"><label for="tab1">'.$this->__("US").'<input name="tab" type="radio" value="us" /></label></li>';
            $html .= '<li id="tab-2"><label for="tab2">'.$this->__("UK").'<input name="tab" type="radio" value="uk" /></label></li>';
            $html .= '<li id="tab-3"><label for="tab3">'.$this->__("EU").'<input name="tab" type="radio" value="eu" /></label></li>';
            $html .= '</ul>';
            $html .= '<div class="tab_container">';
            $html .= '<div id="us" class="tab_content">'.addslashes($collection->getSizeChartUs()).'</div>';
            $html .= '<div id="uk" class="tab_content">'.addslashes($collection->getSizeChartUk()).'</div>';
            $html .= '<div id="eu" class="tab_content">'.addslashes($collection->getSizeChartEu()).'</div>';
            $html .= '</div>';
            $html .= '</div>';
//            $html .= '<\script>';
//            $html .= 'jQuery(document).ready(function($) {
//
//                        //Default Action
//                        $(".tab_content").hide(); //Hide all content
//                        $("ul.tabs li:first").addClass("active").show().find("label input:radio").attr("checked",""); //Activate first tab
//                        $(".tab_content:first").show(); //Show first tab content
//
//                        //On Click Event
//                        $("ul.tabs li").click(function() {
//                            $("ul.tabs li").removeClass("active"); //Remove any "active" class
//                            $("ul.tabs li").find("label input:radio").attr("checked","");
//                            $(this).addClass("active").find("label input:radio").attr("checked","checked");
//                            $(".tab_content").hide(); //Hide all tab content
//                            var activeTab = $(this).find("label input:radio").val(); //Find the href attribute value to identify the active tab + content
//                            $("#" + activeTab).fadeIn(); //Fade in the active ID content
//                            return false;
//
//                        });
//
//                    });';
//            $html .= '<\/script>';
            return $html;
        }
    }
}