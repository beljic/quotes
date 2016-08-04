<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote model
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Model_Quote extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'acme_quotes_quote';
    const CACHE_TAG = 'acme_quotes_quote';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'acme_quotes_quote';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'quote';

    /**
     * constructor
     *
     * @access public
     * @return void

     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('acme_quotes/quote');
    }

    /**
     * before save quote
     *
     * @access protected
     * @return Acme_Quotes_Model_Quote

     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the quote details page
     *
     * @access public
     * @return string

     */
    public function getQuoteUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('acme_quotes/quote/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('acme_quotes/quote/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('acme_quotes/quote/view', array('id'=>$this->getId()));
    }

    /**
     * get the quote Quotation Text
     *
     * @access public
     * @return string

     */
    public function getQuotation()
    {
        $quotation = $this->getData('quotation');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($quotation);
        return $html;
    }

    /**
     * save quote relation
     *
     * @access public
     * @return Acme_Quotes_Model_Quote

     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array

     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        return $values;
    }
    
}
