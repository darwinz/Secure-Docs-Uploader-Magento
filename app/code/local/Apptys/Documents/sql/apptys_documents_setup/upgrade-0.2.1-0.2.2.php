<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->modifyColumn($installer->getTable('apptys_documents/document'),
        'file_name',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => true,
            'comment' => 'Filename'
        ));
$installer->endSetup();