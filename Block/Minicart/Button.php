<?php

namespace Bolt\Boltpay\Block\Minicart;

// use Magento\Checkout\Model\Session;
use Magento\Payment\Model\MethodInterface;
use Magento\Paypal\Model\Config;
// use Magento\Paypal\Model\ConfigFactory;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\ShortcutInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template\Context;
// use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class Button
 */
class Button extends \Bolt\Boltpay\Block\Js implements ShortcutInterface
{
    const ALIAS_ELEMENT_INDEX = 'alias';

    const PAYPAL_BUTTON_ID = 'paypal-express-in-context-checkout-main';

    const BUTTON_ELEMENT_INDEX = 'button_id';

    const LINK_DATA_ACTION = 'link_data_action';

    const CART_BUTTON_ELEMENT_INDEX = 'add_to_cart_selector';

    /**
     * @var bool
     */
    private $isMiniCart = false;

    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var MethodInterface
     */
    private $payment;

    /**
     * @var Session
     */
    private $session;

//    /**
//     * @param Context $context
//     * @param ResolverInterface $localeResolver
//     * @param ConfigFactory $configFactory
//     * @param MethodInterface $payment
//     * @param Session $session
//     * @param array $data
//     */
//    public function __construct(
//        Context $context,
//        ResolverInterface $localeResolver,
//        ConfigFactory $configFactory,
//        Session $session,
//        MethodInterface $payment,
//        array $data = []
//    ) {
//        parent::__construct($context, $data);
//
//        $this->localeResolver = $localeResolver;
//        $this->config = $configFactory->create();
//        $this->config->setMethod(Config::METHOD_EXPRESS);
//        $this->payment = $payment;
//        $this->session = $session;
//
//        $this->setTemplate('Bolt_Boltpay::minicart/button.phtml');
//    }
    /**
     * Button constructor.
     *
     * @param Context                     $context
     * @param \Bolt\Boltpay\Helper\Config $configHelper
     * @param \Magento\Checkout\Model\Session             $checkoutSession
     * @param array                       $data
     */
    public function __construct(
        Context $context,
        \Bolt\Boltpay\Helper\Config $configHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {

        parent::__construct($context, $configHelper, $checkoutSession, $data);

        $this->session = $checkoutSession;
//        $this->localeResolver = $localeResolver;
        $this->localeResolver = null;
//        $this->config = $configFactory->create();
//        $this->config->setMethod(Config::METHOD_EXPRESS);

        $this->setTemplate('Bolt_Boltpay::minicart/button.phtml');
    }


    /**
     * Check `in_context` config value
     *
     * @return bool
     */
    private function isInContext()
    {
        return true;
    }

    /**
     * Check `visible_on_cart` config value
     *
     * @return bool
     */
    private function isVisibleOnCart()
    {
        return true;
    }

    /**
     * Check is Paypal In-Context Express Checkout button
     * should render in cart/mini-cart
     *
     * @return bool
     */
    protected function shouldRender()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * @return string
     */
    public function getContainerId()
    {
        return $this->getData(self::BUTTON_ELEMENT_INDEX);
    }

    /**
     * @return string
     */
    public function getLinkAction()
    {
        return $this->getData(self::LINK_DATA_ACTION);
    }

    /**
     * @return string
     */
    public function getAddToCartSelector()
    {
        return $this->getData(self::CART_BUTTON_ELEMENT_INDEX);
    }

    /**
     * Get shortcut alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->getData(self::ALIAS_ELEMENT_INDEX);
    }

    /**
     * @param bool $isCatalog
     * @return $this
     */
    public function setIsInCatalogProduct($isCatalog)
    {
        $this->isMiniCart = !$isCatalog;

        return $this;
    }
}
