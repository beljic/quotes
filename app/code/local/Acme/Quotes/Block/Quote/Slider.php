<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote list block
 *
 * @category    Acme
 * @package     Acme_Quotes
 */
class Acme_Quotes_Block_Quote_Slider extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public

     */
    public function _construct()
    {
        parent::_construct();
        $quotes = Mage::getResourceModel('acme_quotes/quote_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status', 1)
                        ->setOrder('position', 'asc');
        $this->setQuotes($quotes);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Acme_Quotes_Block_Quote_List

     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        return $this;
    }

}
