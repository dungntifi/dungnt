<?php

require_once 'Mage/Catalog/controllers/CategoryController.php';

class TM_AjaxPro_CatalogController extends Mage_Catalog_CategoryController
{
    /**
     * Category view action
     */
    public function viewAction()
    {

        if (!$this->getRequest()->isXmlHttpRequest()) {
            return parent::viewAction();
        }
        $category = $this->_initCatagory();
        if ($category) {
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }

            Mage::getSingleton('catalog/session')->setLastViewedCategoryId(
                $category->getId()
            );

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            if (!$category->hasChildren()) {
                $update->addHandle('catalog_category_layered_nochildren');
            }

            $this->addActionLayoutHandles();
            $update->addHandle($category->getLayoutUpdateHandle());
            $update->addHandle('CATEGORY_' . $category->getId());
            $this->loadLayoutUpdates();

            // apply custom layout update once layout is loaded
            if ($layoutUpdates = $settings->getLayoutUpdates()) {
                if (is_array($layoutUpdates)) {
                    foreach($layoutUpdates as $layoutUpdate) {
                        $update->addUpdate($layoutUpdate);
                    }
                }
            }

            $this->generateLayoutXml()->generateLayoutBlocks();
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyTemplate(
                    $settings->getPageLayout()
                );
            }

            if ($root = $this->getLayout()->getBlock('root')) {
                $root->addBodyClass('categorypath-' . $category->getUrlPath())
                    ->addBodyClass('category-' . $category->getUrlKey());
            }

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
//            $this->renderLayout();

            $block = $this->getLayout()->getBlock('product_list');
            $toolbarHtml = $block->getToolBarHtml();
            $content = str_replace($toolbarHtml, '', $block->toHtml());
            $content = preg_replace('/\s+/', ' ', $content);
            $content = str_replace(array("\n", "\t",
                '<div class="category-products">',
                '<div class="toolbar-bottom"> </div> </div>'),
            '', $content);

            $session = Mage::getModel('ajaxpro/session');
            $data = array(
                'catalogCategoryView' => $content,
                'status'              => $session->getStatus(),
                'messages'            => $session->getMessages()
            );

            $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-Type', 'application/json')
                ->setBody(Mage::helper('core')->jsonEncode($data))
            ;
        }
    }
}
