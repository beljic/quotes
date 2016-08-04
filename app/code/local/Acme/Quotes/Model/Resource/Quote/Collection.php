<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote collection resource model
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Model_Resource_Quote_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void

     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('acme_quotes/quote');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @access public
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Acme_Quotes_Model_Resource_Quote_Collection

     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!isset($this->_joinedFields['store'])) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }
            if (!is_array($store)) {
                $store = array($store);
            }
            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }
            $this->addFilter('store', array('in' => $store), 'public');
            $this->_joinedFields['store'] = true;
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @access protected
     * @return Acme_Quotes_Model_Resource_Quote_Collection

     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('acme_quotes/quote_store')),
                'main_table.entity_id = store_table.quote_id',
                array()
            )
            ->group('main_table.entity_id');
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }

    /**
     * get quotes as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array

     */
    protected function _toOptionArray($valueField='entity_id', $labelField='name', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array

     */
    protected function _toOptionHash($valueField='entity_id', $labelField='name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select

     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
