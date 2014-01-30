<?php

class ECP_MelissaAddress_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_melissaData = null;

    public function getMelissaData(){
        if ($this->_melissaData === null) {
            $license = Mage::getConfig()->getNode("options/melissa_license")->asArray();
            $datafiles = Mage::getConfig()->getNode("options/melissa_datafiles")->asArray();
            $this->_melissaData = new ECP_MelissaAddress_Model_DataObject($license, $datafiles);
        }
        return $this->_melissaData;
    }

    public function melissaCheckAddress($data) {

        // ********************************************************
        try {
            $melissaData = $this->getMelissaData();
            $res = $melissaData->checkAddress($data);
        } catch (Mage_Exception $e) {
            $res['status'] = 0;
            $res['error'] = $e->getMessage();
        }

        if ($res['status'] == 0) {
            return array(
                'errors'  => array(
                    'Error with address: ',
                    $res['error'],
                ),
                'address' => $data['street'],
            );
        }
        Mage::getSingleton('core/session')->setMelissaData(json_encode($res['address']));
        $streetMelissa = $res['address']['Address'] . ' ' . $res['address']['STE'];
        if (($res['status'] == 1) && ($data['street'][0] != $streetMelissa)) {
            return array(
                'warning'    => array(
                    'Error with address: ',
                    $data['street'][0] . ' ' . $data['city'] . ' ' . $res['address']['State'] . ' ' . $data['postcode'] . '-' . $data['zip4'],
                    $streetMelissa . ' ' . $res['address']['City'] . ', ' . $res['address']['State'] . ' '
                    . $res['address']['Zip'] . '-' . $res['address']['Plus4'],
                ),
                'street'     => $streetMelissa,
                'street_old' => $data['street'][0],
                'zip'        => $res['address']['Zip'],
                'zip4'       => $res['address']['Plus4'],
                'city'       => $res['address']['City'],
                'address'    => $data['street'],
                'isLogin'    => (Mage::getSingleton('customer/session')->isLoggedIn()) ? $data['address_id'] : false
            );
        }

        Mage::getSingleton('core/session')->setMelissaData(json_encode($res['address']));
        return false;

    }

    public function searchCityStateAction($zip) {
            $melissaData = $this->getMelissaData();
            $result = $melissaData->getCityStateByZip($zip);
            if (!$result) {
                $result['error'] = 1;
            }
            return json_encode($result);
        }

}
