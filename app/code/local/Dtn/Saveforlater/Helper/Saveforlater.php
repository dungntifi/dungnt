<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/10/14
 * Time: 9:43 AM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_Helper_Saveforlater extends Mage_Core_Helper_Data{

    public function getCurrentIdSaveMain($model,$customerId){
        $model->getSelect()->where("`customer_id`='".$customerId."'");
        if($model->getSize() > 0){
            foreach($model as $item){
                return $item->getId();
            }
        }
    }

    public function checkExitsCustomerInSaveMain($model,$customerId){
        $model->getSelect()->where("`customer_id`='".$customerId."'");
        if($model->getSize() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function checkExitsProductInSaveItem($models,$mainId,$productId,$option){
        if($mainId){
            $models->getSelect()->where("`main_id`='".$mainId."'");
        }
        if($productId){
            $models->getSelect()->where("`product_id`='".$productId."'");
        }
        if($option){
            $models->getSelect()->where("`option`='".$option."'");
        }else{
            $models->getSelect()->where("1=1");
        }
        if($models->getSize() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getItemId($models,$productId,$option){
        if($productId){
            $models->getSelect()->where("`product_id`='".$productId."'");
        }
        if($option){
            $models->getSelect()->where("`option`='".$option."'");
        }else{
            $models->getSelect()->where("1=1");
        }
        if($models->getSize() > 0){
            foreach($models as $item){
                return $item->getItemId();
            }
        }
    }

}