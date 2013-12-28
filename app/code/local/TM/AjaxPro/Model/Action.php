<?php

class TM_AjaxPro_Model_Action {

    /**
     *
     * @var Mage_Core_Controller_Front_Action
     */
    protected $_controllerAction;

    /**
     *
     * @var TM_AjaxPro_Model_Layout
     */
    protected $_layout;

    /**
     *
     * @var TM_AjaxPro_Model_Session
     */
    protected $_session;

    /**
     *
     * @param Mage_Core_Controller_Front_Action $controllerAction
     * @return \TM_AjaxPro_Model_Observer
     */
    public function setControllerAction(Mage_Core_Controller_Front_Action $controllerAction)
    {
        $this->_controllerAction = $controllerAction;
        return $this;
    }

    /**
     *
     * @return Mage_Core_Controller_Front_Action
     */
    public function getControllerAction()
    {
        return $this->_controllerAction;
    }

    /**
     *
     * @return TM_AjaxPro_Model_Layout
     */
    public function getLayout()
    {
        if(!$this->_layout instanceof TM_AjaxPro_Model_Layout) {
            $this->_layout = Mage::getModel('ajaxpro/layout');
        }
        return $this->_layout;
    }

    /**
     *
     * @return TM_AjaxPro_Model_Session
     */
    public function getSession()
    {
        if(!$this->_session instanceof TM_AjaxPro_Model_Session) {
            $this->_session = Mage::getModel('ajaxpro/session');
        }
        return $this->_session;
    }

    public function dispatch()
    {
        $controllerAction = $this->getControllerAction();
        if (!$controllerAction) {
            return;
        }
        $layout = $this->getLayout();
        $layout->setControllerAction($controllerAction);

        /** @var $request Mage_Core_Controller_Request_Http */
        $request = $controllerAction->getRequest();
        if (!$request->getParam('ajaxpro', false)
            || !$request->isXmlHttpRequest()
            || !Mage::getStoreConfig('ajax_pro/general/enabled')
        ) {

            return;
        }

        $handles = $request->getParam('handles');
        $layout->setHandles($handles)
            ->loadLayout();

        $response = $controllerAction->getResponse();
        if (!$response->isRedirect()) {
            return;
        }
        //$redirectUrl = $response->getHeader('Location');
        $headers = $response->getHeaders();
        $redirectUrl = false;
        foreach($headers as $header) {
            if ('Location' === $header['name']) {
                $redirectUrl = $header['value'];
            }
        }
        //////////////////////////////////////////
        $session = $this->getSession();
        $status  = $session->getStatus();
        $result  = array(
            'status'   => $status,
            'messages' => $session->getMessages()
        );
        if ($status) {
            $_layout = array();
            foreach ($layout->getBlocks() as $block) {
                $html = $layout->getBlockHtml($block);
                if (!empty($html)) {
                    $_layout[$block] = $html;
                }
            }
            if (!empty($_layout)) {
                $result['layout'] = $_layout;
            }
        } else {
            $result['redirectUrl'] = $redirectUrl;
        }

        if ($url = Mage::getSingleton('checkout/session')->getViewCartUrl(true)) {
            $result['viewCartUrl'] = $url;
        }

//        Zend_Debug::dump($response->getHeaders());
//        $response->clearHeaders();
        $response->clearHeader('Location')
            ->setHttpResponseCode(200)
            ->setHeader('Content-Type', 'application/json')
            ->setBody(Mage::helper('core')->jsonEncode($result))
        ;
    }

    public static function isMobile() {
        // https://github.com/mrlynn/MobileBrowserDetectionExample
        $isMobile = false;
        if(isset($_SERVER['HTTP_USER_AGENT'])
                && preg_match('/(android|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {

            $isMobile = true;
        }
        if((isset($_SERVER['HTTP_ACCEPT']) && (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0))
            or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {

            $isMobile = true;
        }

            if(isset($_SERVER['HTTP_USER_AGENT'])) {
            $mobileUserAgent = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
            $mobileAgents = array(
                'w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq',
                'bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco',
                'eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno',
                'lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef',
                'mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm',
                'pana','pant','phil','play','port','prox','qwap','sage','sams',
                'sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem',
                'smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh',
                'tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                'wapr','webc','winw','winw','xda','xda-'
            );

            if(in_array($mobileUserAgent, $mobileAgents)) {
                $isMobile = true;
            }
            }

        if (isset($_SERVER['ALL_HTTP'])) {
            if (strpos(strtolower($_SERVER['ALL_HTTP']), 'OperaMini') > 0) {
                $isMobile = true;
            }
        }
        if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') > 0)) {
            $isMobile = false;
        }
        return $isMobile;
    }

    public static function isSearchBot()
    {
        $spiders = array(
            'Googlebot', 'Baiduspider', 'ia_archiver',
            'R6_FeedFetcher', 'NetcraftSurveyAgent', 'Sogou web spider',
            'bingbot', 'Yahoo! Slurp', 'facebookexternalhit', 'PrintfulBot',
            'msnbot', 'Twitterbot', 'UnwindFetchor',
            'urlresolver', 'Butterfly', 'TweetmemeBot'
        );
        $agent = $_SERVER['HTTP_USER_AGENT'];
        foreach ($spiders as $spider) {
            if(stripos($agent, $spider) !== false) {
                return true;
            }
        }
        return false;
    }
}
