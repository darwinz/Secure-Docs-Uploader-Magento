<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('apptys_documents/document'),
    'filepath',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'comment' => 'Filepath'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('apptys_documents/document'),
    'file',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT
    ));
$installer->endSetup();