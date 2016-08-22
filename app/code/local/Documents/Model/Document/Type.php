<?php
class Apptys_Documents_Model_Document_Type 
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('apptys_documents')->__('Photo')),
            array('value' => 3, 'label'=>Mage::helper('apptys_documents')->__('Video')),
            array('value' => 4, 'label'=>Mage::helper('apptys_documents')->__('Other Document')),
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
            1 => Mage::helper('apptys_documents')->__('Photo'),
            3 => Mage::helper('apptys_documents')->__('Video'),
            4 => Mage::helper('apptys_documents')->__('Other Document'),
        );
    }

    public function getImageExtensionsArray()
    {
        return array(".JPG",".jpg",".PNG",".png",".GIF",".gif",".BMP",".bmp","JPEG","jpeg");
    }
}