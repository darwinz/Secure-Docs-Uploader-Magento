<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('apptys_documents/document'))
	->addColumn('document_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned' => true,
		'nullable' => false,
		'primary' => true,
		'identity' => true,
		), 'Document ID')
	->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable' => false,
		), 'Customer ID') 
	->addColumn('file_name', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
		'nullable' => false,
		), 'Filename') 
	->addColumn('file_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' => true,
		), 'Description')
	->addColumn('file_type', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'nullable' => false,
		), 'File Type')
	->addColumn('file', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
		'nullable' => false,
		), 'File Upload')
	->addColumn('filepath', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
		'nullable' => false,
		), 'File Path')
	->addColumn('approved', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
		'nullable' => false,
		), 'Approved')
	->setComment('Uploaded Documents Table');

$installer->getConnection()->createTable($table);
$installer->endSetup();