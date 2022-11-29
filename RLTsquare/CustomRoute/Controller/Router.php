<?php
declare(strict_types=1);

namespace RLTsquare\CustomRoute\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use RLTsquare\CustomRoute\Logger\Logger;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var Logger
     */
    protected $_logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StoreManagerInterface
     */

    protected $_storeManager;


    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        ActionFactory $actionFactory,
        Logger $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->_logger = $logger;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $identifier = trim($request->getPathInfo(), '/');

        if (strpos($identifier, 'rltsquare') !== false) {
            $request->setModuleName('routing');
            $request->setControllerName('index');
            $request->setActionName('index');

            $this->logData();
            $this->sendMail();
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }

        return null;
    }

    /**
     * @return void
     */
    public function logData()
    {
        $this->_logger->info('Page visited');
    }

    /**
     * @return $this|false
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendMail()
    {
        $variables = [];

        $sender = [
            'name' => $this->scopeConfig->getValue('trans_email/ident_general/name', ScopeInterface::SCOPE_STORE),
            'email' => $this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE)
        ];

        $this->_inlineTranslation->suspend();

        $this->_transportBuilder->setTemplateIdentifier(
            'custom_template'
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $this->_storeManager->getStore()->getId()
            ]
        )->setTemplateVars(
            $variables
        )->setFromByScope(
            $sender
        )->addTo(
            'amna.hamid@rltsquare.com'
        );

        $transport = $this->_transportBuilder->getTransport();

        try {
            $transport->sendMessage();

        } catch (\Exception $exception) {
            return false;
        }
        $this->_inlineTranslation->resume();

        return $this;
    }

}