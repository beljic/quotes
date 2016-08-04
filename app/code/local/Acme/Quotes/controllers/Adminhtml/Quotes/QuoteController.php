<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote admin controller
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Adminhtml_Quotes_QuoteController extends Acme_Quotes_Controller_Adminhtml_Quotes
{
    /**
     * init the quote
     *
     * @access protected
     * @return Acme_Quotes_Model_Quote
     */
    protected function _initQuote()
    {
        $quoteId  = (int) $this->getRequest()->getParam('id');
        $quote    = Mage::getModel('acme_quotes/quote');
        if ($quoteId) {
            $quote->load($quoteId);
        }
        Mage::register('current_quote', $quote);
        return $quote;
    }

    /**
     * default action
     *
     * @access public
     * @return void

     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('acme_quotes')->__('Testimonials'))
             ->_title(Mage::helper('acme_quotes')->__('Testimonials'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void

     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit quote - action
     *
     * @access public
     * @return void

     */
    public function editAction()
    {
        $quoteId    = $this->getRequest()->getParam('id');
        $quote      = $this->_initQuote();
        if ($quoteId && !$quote->getId()) {
            $this->_getSession()->addError(
                Mage::helper('acme_quotes')->__('This quote no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getQuoteData(true);
        if (!empty($data)) {
            $quote->setData($data);
        }
        Mage::register('quote_data', $quote);
        $this->loadLayout();
        $this->_title(Mage::helper('acme_quotes')->__('Testimonials'))
             ->_title(Mage::helper('acme_quotes')->__('Testimonials'));
        if ($quote->getId()) {
            $this->_title($quote->getName());
        } else {
            $this->_title(Mage::helper('acme_quotes')->__('Add testimonial'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new quote action
     *
     * @access public
     * @return void

     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save quote - action
     *
     * @access public
     * @return void

     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('quote')) {
            try {
                $quote = $this->_initQuote();
                $quote->addData($data);
                $imageName = $this->_uploadAndGetName(
                    'image',
                    Mage::helper('acme_quotes/quote_image')->getImageBaseDir(),
                    $data
                );
                $quote->setData('image', $imageName);
                $quote->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('acme_quotes')->__('Quote was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $quote->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setQuoteData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('acme_quotes')->__('There was a problem saving the quote.')
                );
                Mage::getSingleton('adminhtml/session')->setQuoteData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('acme_quotes')->__('Unable to find quote to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete quote - action
     *
     * @access public
     * @return void

     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $quote = Mage::getModel('acme_quotes/quote');
                $quote->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('acme_quotes')->__('Quote was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('acme_quotes')->__('There was an error deleting quote.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('acme_quotes')->__('Could not find quote to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete quote - action
     *
     * @access public
     * @return void

     */
    public function massDeleteAction()
    {
        $quoteIds = $this->getRequest()->getParam('quote');
        if (!is_array($quoteIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('acme_quotes')->__('Please select quotes to delete.')
            );
        } else {
            try {
                foreach ($quoteIds as $quoteId) {
                    $quote = Mage::getModel('acme_quotes/quote');
                    $quote->setId($quoteId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('acme_quotes')->__('Total of %d quotes were successfully deleted.', count($quoteIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('acme_quotes')->__('There was an error deleting quotes.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void

     */
    public function massStatusAction()
    {
        $quoteIds = $this->getRequest()->getParam('quote');
        if (!is_array($quoteIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('acme_quotes')->__('Please select quotes.')
            );
        } else {
            try {
                foreach ($quoteIds as $quoteId) {
                $quote = Mage::getSingleton('acme_quotes/quote')->load($quoteId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d quotes were successfully updated.', count($quoteIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('acme_quotes')->__('There was an error updating quotes.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void

     */
    public function exportCsvAction()
    {
        $fileName   = 'quote.csv';
        $content    = $this->getLayout()->createBlock('acme_quotes/adminhtml_quote_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void

     */
    public function exportExcelAction()
    {
        $fileName   = 'quote.xls';
        $content    = $this->getLayout()->createBlock('acme_quotes/adminhtml_quote_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void

     */
    public function exportXmlAction()
    {
        $fileName   = 'quote.xml';
        $content    = $this->getLayout()->createBlock('acme_quotes/adminhtml_quote_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean

     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/acme_quotes/quote');
    }
}
