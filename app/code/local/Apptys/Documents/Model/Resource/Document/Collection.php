<?php

class Apptys_Documents_Model_Resource_Document_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
            $this->_init('apptys_documents/document');
    }
}