<?php

class TM_AjaxPro_Model_Layout {
    
    /**
     *
     * @var array of string 
     */
    protected $_handles = array();
    
    /**
     *
     * @var xml string
     */
    protected $_layoutXml = '';
    
    /**
     *
     * @var Mage_Core_Controller_Varien_Action 
     */
    protected $_controllerAction;
    
    /**
     *
     * @var array
     */
    protected $_blocks = array();
    
    
    /**
     *
     * @param array $blocks
     * @return \TM_AjaxPro_Model_Layout 
     */
    public function setBlocks(array $blocks) 
    {
        $this->_blocks = $blocks;
        return $this;
    }
    
    /**
     *
     * @return array 
     */
    public function getBlocks() 
    {
        return $this->_blocks;
    }
    
    /**
     *
     * @param Mage_Core_Controller_Varien_Action $controllerAction
     * @return \TM_AjaxPro_Model_Layout 
     */
    public function setControllerAction(Mage_Core_Controller_Varien_Action $controllerAction) 
    {
        $this->_controllerAction = $controllerAction;
        return $this;
    }
    
    /**
     *
     * @param array $handles
     * @return \TM_AjaxPro_Model_Layout 
     */
    public function setHandles(array $handles)
    {
        $this->_handles = $handles;
        return $this;
    }
    
    /**
     *
     * @return \TM_AjaxPro_Model_Layout 
     */
    public function loadLayout()
    {
        $update = $this->_controllerAction->getLayout()
            ->getUpdate();
        foreach ($this->_handles as $handle) {
            $update->addHandle($handle);
        }
        $this->_controllerAction->loadLayout(false, false);
        $this->_layoutXml = $update->asSimplexml();
        return $this;
    }

    private function _addReferences(Mage_Core_Model_Layout_Element $node)
    {
        $references = $this->_layoutXml->xpath("//reference[@name='" . (string)$node['name'] . "']");
        foreach ($references as $reference) {
//            $reference = $this->_addReferences($reference); get error
            $node->appendChild($reference);
        }
        return $node;
    }
    
    /**
     *
     * @param string $blockName
     * @return string 
     */
    public function getBlockHtml($blockName)
    {
        $nodes = $this->_layoutXml->xpath("//block[@name='" . $blockName . "']");

        if (!count($nodes)) {
            return '';
        }
        //hardcoded
        foreach ($nodes as &$node) {
            $node['parent'] = 'root';
            $node = $this->_addReferences($node);
        }//print_r($nodes);die();

        $this->_controllerAction->getLayout()->generateBlocks($nodes);
        $block = $this->_controllerAction->getLayout()->getBlock($blockName);
        
        if (!$block) {
            return '';
        }
        return $block->toHtml();
    }
}
