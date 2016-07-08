<?php
namespace MX\HelperBar\Controller\Adminhtml\Ajax\Cache;

use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

class MassRefresh extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Backend::cache';

    /**
     * @var TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var StateInterface
     */
    protected $_cacheState;

    /**
     * @var Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;


    /**
     * @param Action\Context $context
     * @param TypeListInterface $cacheTypeList
     * @param StateInterface $cacheState
     * @param Pool $cacheFrontendPool
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        TypeListInterface $cacheTypeList,
        StateInterface $cacheState,
        Pool $cacheFrontendPool,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Mass action for cache refresh
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $response = "";
        try {
            $types = $this->getRequest()->getParam('types');
            $updatedTypes = 0;
            if (!is_array($types)) {
                $types = [];
            }
            $this->_validateTypes($types);
            foreach ($types as $type) {
                $this->_cacheTypeList->cleanType($type);
                $updatedTypes++;
            }
            if ($updatedTypes > 0) {
                $response = ['success' => true, 'message' => __("%1 cache type(s) refreshed.", $updatedTypes)];
            }
        } catch (LocalizedException $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => __('An error occurred while refreshing cache.')];
        }

        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    /**
     * Check whether specified cache types exist
     *
     * @param array $types
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function _validateTypes(array $types)
    {
        if (empty($types)) {
            return;
        }
        $allTypes = array_keys($this->_cacheTypeList->getTypes());
        $invalidTypes = array_diff($types, $allTypes);
        if (count($invalidTypes) > 0) {
            throw new LocalizedException(__('Specified cache type(s) don\'t exist: %1', join(', ', $invalidTypes)));
        }
    }
}
