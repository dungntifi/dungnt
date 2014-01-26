<?php
/**
 * MelissaAddress DataObject service model 
 */

class ECP_MelissaAddress_Model_DataObject
{
    const OBJ_ALL = 'ALL';
    /**
     * Flag initialize dataobject
     *
     * @var bool
     */
    protected $_initialized;

    protected $_license;

    protected $_datafiles;
    /**
     * Class constructor
     *
     * 
     */
    public function __construct($license,$datafiles)
    {
        if (!extension_loaded('mdAddrPHP')) {

            if (!function_exists('mdAddrSetAddress')) {
                try {
                    Mage::throwException('Not extension mdAddrPHP loaded.');
                } catch (Exception $e){
                    Mage::logException($e);
                    throw $e;
                }
            }
        }

        $this->_license = $license;
        $this->_datafiles = $datafiles;

        $this->init();   

        if (mdAddrInitializedataFiles() != 0) {
            throw new Mage_Exception(mdAddrGetInitializeErrorString());
        }
        $this->_initialized = true;
        
    }
    /**
     * Init data object 
     *
     * @param string $license
     * @param string $datafiles
     */
    public function init($object = 'ALL'){
        
        mdAddrClearProperties();
        //Create mdAddress Object
        mdAddrCreate();
        //Set Master License String
        mdAddrSetLicenseString($this->_license);
        // Initialize data paths
        mdAddrSetPathToUSFiles($this->_datafiles);
        //mdAddrSetPathToDPVDFiles("PathtoDPVFiles");

        mdZipCreate();
        mdZipSetLicenseString($this->_license);
        // Initialize
        mdZipInitialize($this->_datafiles,$this->_datafiles,"");


        
    }

    public function getCityStateByZip($zip){
        $results = array();
        if (mdZipFindZip($zip,0)){

            do {
                $arr = array('city'=>trim(mdZipGetCity()),'state'=> mdZipGetState());
                if (mdZipGetLastLineIndicator()=="L"){
                    array_unshift($results,$arr);
                } else {
                    array_push($results,$arr);
                }
            } while (mdZipFindZipNext()==1);

        } else {
            return false;
        }
        return array_shift($results);
    }
    
     /**
     * Check address
     *
     * @param array $data
     * @return array
     */
    public function checkAddress($data){
        
        if (!$this->_initialized) return;
        
        if (isset($data['street']) && is_array($data['street'])){
            $data['address'] = $data['street'][0];
            $data['address2'] = $data['street'][1];
            unset($data['street']);
        } elseif (isset($data['street'])){
            $data['address'] = $data['street'];
            unset($data['street']);
        }
        if (isset($data['postcode'])) {
            $data['zip'] = $data['postcode'];
            unset($data['postcode']);
        }
        if (isset($data['zip4'])){
            $data['Plus4'] = $data['zip4'];
            unset($data['zip4']);
        }
        if (isset($data['region'])){
            $data['state'] = $data['region'];
            unset($data['region']);
        }
        
        foreach ($data as $key => $value) {
            $this->setOption($key, $value);
            
        }
        $result = $this->verifyAddress();
        $verif = false;
        switch ($result['codeStatus'])
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
        //if ($result['status'] == 1) {
        //    $result['address'] = $this->getAddress();
        //}
        
        return $result;
    }
    
     /**
     * Get assigned address array
     *
     * @return array
     */
    public function getAddress(){
        
        if (!$this->_initialized) return;
        
        $data = array();
        $data['Company'] =  mdaddrGetCompany();
	$data['Address'] =  mdaddrGetAddress();
	$data['Address2'] =  mdaddrGetAddress2();
	$data['City'] =  mdaddrGetCity();
	$data['State'] =  mdaddrGetState();
	$data['Zip'] =  mdaddrGetZip();
	$data['Plus4'] =  mdaddrGetPlus4();
	$data['STE'] =  mdaddrGetSuite();
	//$data[''] =  " STE Status 		: ".mdaddrGetSuiteStatus();
	$data['CRRT'] =  mdaddrGetCarrierRoute();
	$data['DPC'] =  mdaddrGetDeliveryPointCode();
	//$data[''] =  mdaddrGetDeliveryPointCheckDigit();
	$data['CityAbbr'] = mdaddrGetCityAbbreviation();
	$data['County'] =  mdaddrGetCountyName();
	$data['Country'] =  mdaddrGetCountryCode();
	$data['FIPS'] =  mdaddrGetCountyFips();
	$data['AddrType'] =  mdaddrGetAddressTypeCode();
	$data['AddrTypeStr'] =  mdaddrGetAddressTypeString();
	$data['ZipType'] =  mdaddrGetZipType();
	$data['Urbanization'] =  mdaddrGetUrbanization();
	$data['CongressDist'] =  mdaddrGetCongressionalDistrict();
	//$data[''] =  " LACS 			: ".a_GetLACS();
	//$data[''] =  " LACS Count 		: ".&mdAddrPerl::mdAddrGetLacsCount();
	$data['TZ'] =  mdaddrGetTimeZone();
	$data['TZC'] =  mdaddrGetTimeZoneCode();
	$data['MSA'] =  mdaddrGetMsa();
	$data['PMSA'] =  mdaddrGetPmsa();
	$data['DFI'] =  mdaddrGetDefaultFlagIndicator();
	$data['status_code'] =  mdaddrGetStatusCode();
	$data['error_code'] =  mdaddrGetErrorCode();
	$data['error_message'] =  mdaddrGetErrorString();
	$data['DPV'] =  mdaddrGetDPVFootnotes();
	$data['RBDI'] =  mdaddrGetRBDI();
        
        return $data;
    }
    /**
     * Verify Address
     *
     * @return array
     */
    protected function verifyAddress(){
        
        if (!$this->_initialized) return;
        
        $result = mdAddrVerifyAddress();
        $codeStatus = mdAddrGetStatusCode();
        $codeError = mdAddrGetErrorCode();

       return array('status'=>$result,'codeStatus'=>$codeStatus,'codeError'=>$codeError);
    }

    /**
     * Set Options address
     *
     * @param   string $key
     * @param   string $value
     * @return  bool
     */
    protected function setOption($key,$value){
        
        $key = ucfirst(strtolower($key));
        $function = 'mdAddrSet'.$key;
        if (!function_exists($function)){
            return false;
        } 
        $function($value);
        
        return true;
    }

}
