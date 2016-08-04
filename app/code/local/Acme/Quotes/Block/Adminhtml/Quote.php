<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote admin block
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Block_Adminhtml_Quote extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_quote';
        $this->_blockGroup         = 'acme_quotes';
        parent::__construct();
        $this->_headerText         = Mage::helper('acme_quotes')->__('Testimonial');
        $this->_updateButton('add', 'label', Mage::helper('acme_quotes')->__('Add Testimonial'));

    }
}
