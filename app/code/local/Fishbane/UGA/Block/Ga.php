<?php
class Fishbane_UGA_Block_Ga extends Mage_GoogleAnalytics_Block_Ga
{
   protected function _getPageTrackingCode($accountId)
    {
        $pageName   = trim($this->getPageName());
        $optPageURL = '';
        if ($pageName && preg_match('/^\/.*/i', $pageName)) {
            $optPageURL = ", '{$this->jsQuoteEscape($pageName)}'";
        }
        return "
  ga('create', '{$this->jsQuoteEscape($accountId)}', 'auto');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');
";
    }
}
