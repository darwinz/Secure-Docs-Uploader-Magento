<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('apptys_documents/document'),
    'filehash',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'File Hash'
    ));
$installer->endSetup();