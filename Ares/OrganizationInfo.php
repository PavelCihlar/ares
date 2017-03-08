<?php
namespace Ares;

/**
 * Default class for Reading info from ARES
 *
 * @author Pavel Cihlář <cihlar.pavel84@gmail.com>
 */
class OrganizationInfo {
    
    const ARES_URL = 'http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi?ico=';
    
    /**
     * company ICO
     *
     * @var int
     */    
    private $ico;

    /**
     * response data from Ares
     *
     * @var string
     */    
    private $response;

    /**
     * result
     *
     * @var array
     */    
    private $result = array();
    
    /**
     * status of result
     *
     * @var int
     */    
    private $resultStatus;    
    
    /**
     * constructor
     * 
     * @param int ico
     */    
    public function __construct($ico) {
        $this->ico = $ico;
        
        if ( !$this->checkIco() ) {
            $this->setResultStatus('Neplatné IČ');
            return;
        }        
        
        $this->crawleResponse();
        $this->procesResponse();
    }
    
    /**
     * crawleResponse
     */
    private function crawleResponse() {
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, self::ARES_URL . $this->ico); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HEADER, false); 
        $this->response = curl_exec($curl); 
        curl_close($curl);
    }

    /**
     * procesResponse
     */
    private function procesResponse() {
        $responseArray = xml2array($this->response);
        if ( isset($responseArray['are:Ares_odpovedi']['are:Odpoved']['D:VBAS']) ) {
            $responseInfo = $responseArray['are:Ares_odpovedi']['are:Odpoved']['D:VBAS'];
            
            $result = array();
            $result['dic'] = $responseInfo['D:DIC'];
            $result['name'] = $responseInfo['D:OF'];
            $result['name'] = $responseInfo['D:OF'];
            $result['city'] = $responseInfo['D:AA']['D:NCO'];
            $result['street'] = $responseInfo['D:AA']['D:NU'];
            $result['descriptionnumber'] = $responseInfo['D:AA']['D:CD'];
            $result['zipcode'] = $responseInfo['D:AA']['D:PSC'];
            
            $this->setResult($result);
            $this->setResultStatus('Ok');
        } else {
            $this->setResultStatus('IČ nenalezeno');
        }
    }
    
    /**
     * checkIco
     */    
    private function checkIco() {
        $ic = preg_replace('#\s+#', '', $this->ico);

        if (!preg_match('#^\d{8}$#', $ic)) {
            $this->setResultStatus('Neplatné IČ');
            return false;
        }
        
        return true;
    }

    /**
     * setResultStatus
     * 
     * @param string resultStatus
     */       
    private function setResultStatus($resultStatus) {
        $this->resultStatus = $resultStatus;
    }

    /**
     * getResultStatus
     * 
     * @return string resultStatus
     */       
    public function getResultStatus() {
        return $this->resultStatus;
    }

    /**
     * setResult
     * 
     * @param string result
     */       
    private function setResult($result) {
        $this->result = $result;
    }

    /**
     * getResult
     * 
     * @return string result
     */       
    public function getResult() {
        return $this->result;
    }    
}