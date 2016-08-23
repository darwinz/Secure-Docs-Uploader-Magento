<?php

class Apptys_Documents_Block_Adminhtml_Documents_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('apptys_documents_grid');
      $this->setDefaultSort('document_id');
      $this->setDefaultDir('ASC');
      //error_log('accessed documents');
      $this->setSaveParametersInSession(true);
      $this->setUseAjax(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('apptys_documents/document')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $helper = Mage::helper('apptys_documents');
      //$fileTypes = Mage::getSingleton('apptys_documents/document_type')
      //->getAllOptions();
      /*$this->addColumn('document_id', array(
          'header'    => $helper->__('ID'),
          //'align'     =>'right',
          //'width'     => '50px',
          'index'     => 'document_id',
      ));*/

      $this->addColumn('file_name', array(
          'header'    => $helper->__('Filename'),
          //'align'     =>'left',
          'index'     => 'file_name',
      ));

      $this->addColumn('file_description', array(
          'header'    => $helper->__('Description'),
          //'align'     => 'left',
          //'width'     => '80px',
          'index'     => 'file_description',
      ));

      $this->addColumn('file_type', array(
          'header'    => $helper->__('File Type'),
          //'align'     =>'left',
          'type'      => 'options',
          'index'     => 'file_type',
          'options'   => Mage::getSingleton('apptys_documents/document_type')->toArray(),
      ));

      $this->addColumn('approved', array(
          'header'    => $helper->__('Approved'),
          //'type'      => 'boolean',
          //'align'     =>'left',
          'index'     => 'approved',
          'type'      => 'options',
          'options'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray(),
      ));

             
      $this->addExportType('*/*/exportApptysDocumentCsv', $helper->__('CSV'));
      $this->addExportType('*/*/exportApptysDocumentExcel', $helper->__('XML'));
     
      return parent::_prepareColumns();
  }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('documents')) {
            return $this->getUrl('*/managedoc/edit', array('id' => $row->getId()));
        }
        return false;
    }

  public function getGridUrl()
  {
      return $this->getUrl('*/*/grid', array('_current' => true));
  }

}
