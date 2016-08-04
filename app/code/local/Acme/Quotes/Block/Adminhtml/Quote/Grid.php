<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote admin grid block
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Block_Adminhtml_Quote_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public

     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('quoteGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Acme_Quotes_Block_Adminhtml_Quote_Grid

     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('acme_quotes/quote')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Acme_Quotes_Block_Adminhtml_Quote_Grid

     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('acme_quotes')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('acme_quotes')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('acme_quotes')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('acme_quotes')->__('Enabled'),
                    '0' => Mage::helper('acme_quotes')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'author',
            array(
                'header' => Mage::helper('acme_quotes')->__('Author'),
                'index'  => 'author',
                'type'=> 'text',

            )
        );
       /* $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('acme_quotes')->__('URL key'),
                'index'  => 'url_key',
            )
        );
       */
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('acme_quotes')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'Position',
            array(
                'header'  => Mage::helper('acme_quotes')->__('Position'),
                'index'   => 'position',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('acme_quotes')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('acme_quotes')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('acme_quotes')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('acme_quotes')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('acme_quotes')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('acme_quotes')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('acme_quotes')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Acme_Quotes_Block_Adminhtml_Quote_Grid

     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('quote');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('acme_quotes')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('acme_quotes')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('acme_quotes')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('acme_quotes')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('acme_quotes')->__('Enabled'),
                            '0' => Mage::helper('acme_quotes')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Acme_Quotes_Model_Quote
     * @return string

     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string

     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Acme_Quotes_Block_Adminhtml_Quote_Grid

     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Acme_Quotes_Model_Resource_Quote_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Acme_Quotes_Block_Adminhtml_Quote_Grid

     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
