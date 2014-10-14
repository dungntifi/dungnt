<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/9/14
 * Time: 2:46 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_Model_Mysql4_Savemain_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{
    public function _construct(){
        parent::_construct();
        $this->_init('saveforlater/savemain');
    }
}