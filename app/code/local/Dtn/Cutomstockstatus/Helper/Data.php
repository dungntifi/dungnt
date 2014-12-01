<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 11/27/14
 * Time: 9:44 AM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Cutomstockstatus_Helper_Data extends Mage_Core_Helper_Url{
    public function setQtyOfProduct($item){
        $product = Mage::getModel('catalog/product')->load($item->getProductId());
        $this->checkEnableTypeQty($product,$item->getQty());
        return $this;
    }

    public function checkEnableTypeQty($product,$qty){
        $instock = array($product->getEnableInstock(), $product->getQtyInStock(), 'qty_in_stock');
        $vendor = array($product->getEnableVendorstock(), $product->getQtyVendorstock(), 'qty_vendorstock');
        $preoder = array($product->getEnablePreorderstock(), $product->getQtyPreorderstock(), 'qty_preorderstock');
        $vendorPreOder = array($product->getEnableVendorPreorderstock(), $product->getQtyVendorPreorderstock(), 'qty_vendor_preorderstock');
        $special = array($product->getEnableSpecialorderstock(), $product->getQtySpecialorderstock(), 'qty_specialorderstock');
        $unknown = array($product->getEnableUnknown(), $product->getQtyUnknown(), 'qty_unknown');
        $array = array($instock,$vendor,$preoder,$vendorPreOder,$special,$unknown);
        foreach($array as $item){
            if($qty == 0) break;
            if($item[0]==1){
                if($item[1] >= $qty){
                    $item[1] = $item[1] - $qty;
                    $qty = 0;
                }else{
                    $qty = $qty - $item[1];
                    $item[1] = 0;
                }
                $product->setData($item[2],$item[1]);
            }else{continue;}
        }
        $product->save();
    }

    public function getTotalQtyOfItem($product){
        //$product = Mage::getModel('catalog/product')->load($item->getProductId());
        $instock = array($product->getEnableInstock(), $product->getQtyInStock());
        $vendor = array($product->getEnableVendorstock(), $product->getQtyVendorstock());
        $preoder = array($product->getEnablePreorderstock(), $product->getQtyPreorderstock());
        $vendorPreOder = array($product->getEnableVendorPreorderstock(), $product->getQtyVendorPreorderstock());
        $special = array($product->getEnableSpecialorderstock(), $product->getQtySpecialorderstock());
        $unknown = array($product->getEnableUnknown(), $product->getQtyUnknown());
        $array = array($instock,$vendor,$preoder,$vendorPreOder,$special,$unknown);
        $total = 0;
        foreach($array as $item){
            if($item[0]==1){
                $total += $item[1];
            }
        }
        return $total;
    }

    public function getTotalQtyOfItemInCart($sale){
        $product = Mage::getModel('catalog/product')->load($sale->getProductId());
        $instock = array($product->getEnableInstock(), $product->getQtyInStock());
        $vendor = array($product->getEnableVendorstock(), $product->getQtyVendorstock());
        $preoder = array($product->getEnablePreorderstock(), $product->getQtyPreorderstock());
        $vendorPreOder = array($product->getEnableVendorPreorderstock(), $product->getQtyVendorPreorderstock());
        $special = array($product->getEnableSpecialorderstock(), $product->getQtySpecialorderstock());
        $unknown = array($product->getEnableUnknown(), $product->getQtyUnknown());
        $array = array($instock,$vendor,$preoder,$vendorPreOder,$special,$unknown);
        $total = 0;
        foreach($array as $item){
            if($item[0]==1){
                $total += $item[1];
            }
        }
        return $total;
    }

    public function getCustomStockStatus(Mage_Catalog_Model_Product $product){
        if(!$product)
            return false;
        $status = '';
        $product = Mage::getModel('catalog/product')->load($product->getId());
        $instock = array($product->getEnableInstock(), $product->getQtyInStock(), 'status_instock');
        $vendor = array($product->getEnableVendorstock(), $product->getQtyVendorstock(), 'status_vendorstock');
        $preoder = array($product->getEnablePreorderstock(), $product->getQtyPreorderstock(), 'status_preorderstock', 'date_preorderstock');
        $vendorPreOder = array($product->getEnableVendorPreorderstock(), $product->getQtyVendorPreorderstock(), 'status_vendor_preorderstock', 'date_vendor_preorderstock');
        $special = array($product->getEnableSpecialorderstock(), $product->getQtySpecialorderstock(), 'status_specialorderstock', 'date_specialorderstock', 'numberofdays_stock');
        $unknown = array($product->getEnableUnknown(), $product->getQtyUnknown(), 'status_unknown');
        $array = array($instock,$vendor,$preoder,$vendorPreOder,$special,$unknown);
        foreach($array as $item){
            if($item[0]==1 && $item[1] > 0){
                $status .= $product->getData($item[2]);
                if ($item[3] && false !== strpos($status, '{date}')){
                    if(!$item[4]){
                        $status = str_replace('{date}', date('Y-m-d', strtotime($product->getData($item[3]))), $status);
                    }else{
                        if(Mage::getModel('core/date')->date('Y-m-d') < date('Y-m-d', strtotime($product->getData($item[3])))){
                            $newdate = $product->getData($item[3]);
                        }else{
                            $newdate = date("Y-m-d", strtotime($product->getData($item[3]))) . " + ".(int)$product->getData($item[4])." days";
                        }
                        $status = str_replace('{date}', date('Y-m-d', strtotime($newdate)), $status);
                    }
                }
                return $status;
            }else{
                continue;
            }
        }
    }
}