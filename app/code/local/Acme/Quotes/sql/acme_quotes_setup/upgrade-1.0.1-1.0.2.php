<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quotes module install script
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
$this->startSetup();

$this->getConnection()->addColumn($this->getTable('acme_quotes/quote'), 'position', 'int(5) unsigned NOT NULL DEFAULT 0');

$this->endSetup();
