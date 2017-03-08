<?php
namespace Ares;

use Exception;

/**
 * AresException
 *
 * @author Pavel Cihlář <cihlar.pavel84@gmail.com>
 */
class AresException extends Exception {
    
    /**
     * Constructor
     *
     * @param string message
     * @param string code
     */    
    public function __construct($message = null, $code = 0) {
        parent::__construct('ARES Exception: ' . $message, $code);
    }
}