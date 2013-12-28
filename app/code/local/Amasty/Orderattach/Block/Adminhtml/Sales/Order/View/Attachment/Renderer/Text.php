<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Orderattach
*/
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_View_Attachment_Renderer_Text extends Amasty_Orderattach_Block_Adminhtml_Sales_Order_View_Attachment_Renderer_String
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/renderer/text.phtml');
    }
}