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
}