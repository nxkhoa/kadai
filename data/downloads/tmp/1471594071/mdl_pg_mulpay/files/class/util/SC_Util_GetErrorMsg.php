<?php
/**
 *
 * @copyright 2012 GMO Payment Gateway, Inc. All Rights Reserved.
 * @link http://www.gmo-pg.com/
 *
 */


class SC_Util_GetErrorMsg {
    public $arrError = null;

    function __construct() {
        $this->_loadErrors(MDL_PG_MULPAY_ERROR_CODE_MSG_FILE);
    }

    function lfGetErrorInformation($code) {
        if (!$code) return false;
        if (!$this->arrError) return false;
        if (!isset($this->arrError[$code])) return false;
        return $this->arrError[$code];
    }

    function _loadErrors($filename) {
        if ($this->arrError) return;
        $this->arrError = $this->_getErrors($filename);
        if (!$this->arrError) echo $pdf_filename . ' かデータの作成が行えませんでした。';
    }

    function _getErrors($filename) {
        $arrError = array();

        $text = file_get_contents($filename);
        $arrText = explode("\n", $text);
        foreach ($arrText as $line) {
            $arrLine = explode("\t", $line);
            $struct = $this->_setStruct($arrLine);
            $code = $struct['code'];
            $arrError[$code] = $struct;
        }
        return $arrError;
    }

    function _setStruct($arrLine = null) {
        $array = array();
        $array['code']    = (isset($arrLine[0])) ? $arrLine[0] : "";
        $array['no']      = (isset($arrLine[1])) ? $arrLine[1] : "";
        $array['s_code']  = (isset($arrLine[2])) ? $arrLine[2] : "";
        $array['d_code']  = (isset($arrLine[3])) ? $arrLine[3] : "";
        $array['status']  = (isset($arrLine[4])) ? $arrLine[4] : "";
        $array['payment'] = (isset($arrLine[5])) ? $arrLine[5] : "";
        $array['context'] = (isset($arrLine[6])) ? $arrLine[6] : "";
        $array['message'] = (isset($arrLine[7])) ? $arrLine[7] : "";
        return $array;
    }
}

