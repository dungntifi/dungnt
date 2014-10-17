<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/16/14
 * Time: 3:40 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_All_IndexController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){
        $this->loadLayout();
//        print_r($this->getLayout()->getUpdate()->getHandles());die;
        $param = $this->getRequest()->getParam('letter');
        if($param){
            $letter = $param;
        }else{
            $letter = "";
        }
        $block = $this->getLayout()->createBlock('amshopby/list','designer-list',array('letter'=>$letter, 'attribute_code'=>'designer' ,'columns'=>'2', 'template' => 'amshopby/list.phtml'));
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }
}