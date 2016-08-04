<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote admin edit tabs
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Block_Adminhtml_Quote_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public

     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('quote_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('acme_quotes')->__('Testimonial'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Acme_Quotes_Block_Adminhtml_Quote_Edit_Tabs

     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_quote',
            array(
                'label'   => Mage::helper('acme_quotes')->__('Testimonial'),
                'title'   => Mage::helper('acme_quotes')->__('Testimonial'),
                'content' => $this->getLayout()->createBlock(
                    'acme_quotes/adminhtml_quote_edit_tab_form'
                )
                ->toHtml(),
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_quote',
                array(
                    'label'   => Mage::helper('acme_quotes')->__('Store views'),
                    'title'   => Mage::helper('acme_quotes')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'acme_quotes/adminhtml_quote_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve quote entity
     *
     * @access public
     * @return Acme_Quotes_Model_Quote

     */
    public function getQuote()
    {
        return Mage::registry('current_quote');
    }
}
