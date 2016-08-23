<?php

class Apptys_Documents_Model_Resource_Document extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('apptys_documents/document', 'document_id');
    }
}