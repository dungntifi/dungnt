<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/9/14
 * Time: 2:30 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_Model_Savemain extends Mage_Core_Model_Abstract {
    public function _construct(){
        parent::_construct();
        $this->_init('saveforlater/savemain');
    }
}