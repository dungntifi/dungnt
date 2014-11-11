<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 6/24/13
 * Time: 6:26 PM
 * To change this template use File | Settings | File Templates.
 */
class ECP_LinkShare_Helper_Data extends Mage_Core_Helper_Abstract {

    public function __construct()
    {
        Mage::getConfig()->loadModulesConfiguration('ecp_linkshare.xml', Mage::getConfig());
    }

    public function ftpUpload($arr_files)
    {
        if (!extension_loaded('ftp')) {
            Mage::log('FTP PHP extension is not installed', null, 'linkshare.log');
            die;
        }
        $conf = Mage::getStoreConfig('link_share_settings/ftp');
        if (!($conn = ftp_connect($conf['host'], $conf['port']))) {
            Mage::log('Could not connect to FTP host', null, 'linkshare.log');
            die;
        }
        $password = $conf['password'];

        if (!@ftp_login($conn, $conf['user'], $password)) {
            ftp_close($conn);
            Mage::log('Could not login to FTP host', null, 'linkshare.log');
            die;
        }
        /*if (!@ftp_chdir($conn, $conf['path'])) {
            ftp_close($conn);
            Mage::throwException($this->__('Could not navigate to FTP path'));
        }*/
        ftp_pasv ( $conn , true );
        $localPath = Mage::getBaseDir() . '/' . $conf['localPath'];
        $errors = $this->ftpUploadFiles($conn, $arr_files, $localPath);

        ftp_close($conn);

        return $errors;
    }

    public function ftpUploadFiles($conn, $arr_files, $localPath)
    {
        $errors = array();
        foreach ($arr_files as $file) {
            if (ftp_put($conn, $file, $localPath.'/'.$file, FTP_ASCII)) {
                Mage::log('file '.$file.' uploaded', null, 'linkshare.log');
            } else {
                $errors[] = ftp_pwd($conn).'/'.$file;
                Mage::log($localPath.'/'.$file, null, 'linkshare.log');
                Mage::log('upload failed. file: '.$file, null, 'linkshare.log');
            }
            continue;
        }
        return $errors;
    }

}