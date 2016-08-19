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
    protected $cacheTypeList;

    /**
     * @var StateInterface
     */
    protected $cacheState;

    /**
     * @var Pool
     */
    protected $cacheFrontendPool;

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
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheState = $cacheState;
        $this->cacheFrontendPool = $cacheFrontendPool;
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
            $labels = $this->getRequest()->getParam('labels');
            $updatedTypes = 0;
            if (!is_array($labels)) {
                $labels = [];
            }

            $labels = array_map("trim", $labels);
            $types = $this->getTypes($labels);

            foreach ($types as $type) {
                $this->cacheTypeList->cleanType($type);
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


    private function getTypes(array $labels)
    {
        $cacheTypes = [];
        foreach ($this->cacheTypeList->getTypes() as $id => $cacheType) {
            $cacheTypes[$cacheType->getCacheType()] = $id;
        }

        if (array_search("All", $labels) !== false) {
            return array_values($cacheTypes);
        }

        $types = [];
        foreach ($labels as $label) {
            if(array_key_exists($label, $cacheTypes)) {
                $types[] = $cacheTypes[$label];
            }
        }

        return $types;
    }
}
