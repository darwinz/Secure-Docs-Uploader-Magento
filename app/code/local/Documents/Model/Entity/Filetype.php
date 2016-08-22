<?php
class Apptys_Customer_Model_Entity_Filetype 
{
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = array();
            /*$this->_options[] = array(
                    'value' => '',
                    'label' => 'Choose Option..'
            );*/
            $this->_options[] = array(
                    'value' => 1,
                    'label' => 'Photo'
            );
            $this->_options[] = array(
                    'value' => 2,
                    'label' => 'Video'
            );
            $this->_options[] = array(
                    'value' => 3,
                    'label' => 'Other Document'
            );
             
        }
 
        return $this->_options;
    }
}