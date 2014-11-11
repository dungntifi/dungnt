<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/30/14
 * Time: 11:34 AM
 * To change this template use File | Settings | File Templates.
 */

class Dtn_Rest_Helper_Data extends  Mage_Core_Helper_Abstract{
    private $_melissaUrl = null;

    public function getUrlRestDefault(){
        $url = Mage::getConfig()->getNode("options/url")->asArray();
        $customerId = Mage::getConfig()->getNode("options/customerID")->asArray();
        $this->_melissaUrl = $url.'?id='.$customerId;
        return $this->_melissaUrl;
    }

    public function getCityStateByZip($zip){
        $url = $this->getUrlRestDefault().'&zip='.$zip;
        $response = file_get_contents($url);
        $response = new SimpleXMLElement($response);
        $city = (string)$response->Record[0]->Address[0]->City->Name;
        $state = (string)$response->Record[0]->Address[0]->State->Abbreviation;
        $results = array('city'=> $city,'state'=> $state);
        return $results;
    }

    public function verifyAddress($zip){
        $url = $this->getUrlRestDefault().'&zip='.$zip;
        $response = file_get_contents($url);
        $response = new SimpleXMLElement($response);
        $status = $response->Record[0]->Results;
        var_dump($status);die;
    }

    public function searchCityStateAction($zip,$json_encode = true) {
        $result = $this->getCityStateByZip($zip);
        if (!$result) {
            $result['error'] = 1;
        }
        return ($json_encode)?json_encode($result):$result;
    }

    public function checkAddress($data){
        $url = $this->getUrlRestDefault();
        if (isset($data['street']) && is_array($data['street'])){
            $data['a1'] = $data['street'][0];
            $data['a2'] = $data['street'][1];
            $url .= '&a1='.$data['a1'].'&a2='.$data['a2'];
            unset($data['street']);
        } elseif (isset($data['street'])){
            $data['a1'] = $data['street'];
            $url .= '&a1='.$data['a1'];
            unset($data['street']);
        }
        if (isset($data['postcode'])) {
            $data['zip'] = $data['postcode'];
            $url .= '&zip='.$data['zip'];
            unset($data['postcode']);
        }
        if (isset($data['region'])){
            $data['state'] = $data['region'];
            $url .= '&state='.$data['region'];
            unset($data['region']);
        }
        $response = file_get_contents($url);
        $response = new SimpleXMLElement($response);
        $status = $response->Record[0]->Results;
        var_dump($status);die;
        $verif = false;
        switch ($status)
        {
            case "9":
                $status = "Address Verified to ZIP+4";
                $verif = true;
                break;
            case "7":
                $status = "Address Verified to Carrier Route";
                $verif = true;
                break;
            case "5":
                $status = "Address Verified to 5-digit ZIP Code";
                $verif = true;
                break;
            case "S":
                $status = "Address Standardized Only";
                break;
            case "X":
                $status = "Address Not Verified";
                break;
            case "D":
                $status = "Demo Mode";
                break;
            case "E":
                $status = "Expired Database";
                break;
            default:
                $status = "Other Status=" .mdAddrGetStatusCode();
                break;
        }
        if ($verif){

            $result['address'] = $this->getAddress();

        } else {

            switch ($result['codeError'])
            {
                case "M":
                    $errorStatus = "Multiple Matches";
                    break;
                case "N":
                    $errorStatus = "No Data Available For City";
                    break;
                case "R":
                    $errorStatus = "Range Error";
                    break;
                case "T":
                    $errorStatus = "Component Error";
                    break;
                case "U":
                    $errorStatus = "Unknown Street";
                    break;
                case "X":
                    $errorStatus = "Undeliverable Address";
                    break;
                case "Z":
                    $errorStatus = "Invalid ZIP Code";
                    break;
                case "C":
                    $errorStatus = "Canadian ZIP Code";
                    break;
                case "D":
                    $errorStatus = "Demo Mode Only";
                    break;
                default:
                    $errorStatus = mdAddrGetErrorString();
                    break;
            }


        }
        $result['status'] = (int) $verif;
        $result['error'] = $errorStatus;
        return $result;
    }
}