<?php
class Apptys_Documents_Model_Document_Status
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('apptys_documents')->__('Pending...')),
            array('value' => 1, 'label'=>Mage::helper('apptys_documents')->__('Approved')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0 => Mage::helper('apptys_documents')->__('Pending...'),
            1 => Mage::helper('apptys_documents')->__('Approved'),
        );
    }
}