<?php

namespace Bolt\Boltpay\Observer;

use Magento\Paypal\Helper\Shortcut\Factory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Paypal\Model\Config as PaypalConfig;
use Magento\Framework\Event\Observer as EventObserver;

/**
 * PayPal module observer
 */
class AddBoltPayShortcutsObserver implements ObserverInterface
{
    /**
     * @var Factory
     */
    protected $shortcutFactory;

    /**
     * @var PaypalConfig
     */
    protected $paypalConfig;

    /**
     * Constructor
     *
     * @param Factory $shortcutFactory
     * @param PaypalConfig $paypalConfig
     */
    public function __construct(
        Factory $shortcutFactory,
        PaypalConfig $paypalConfig
    ) {
        $this->shortcutFactory = $shortcutFactory;
        $this->paypalConfig = $paypalConfig;
    }

    /**
     *
     * @param EventObserver $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Catalog\Block\ShortcutButtons $shortcutButtons */
        $shortcutButtons = $observer->getEvent()->getContainer();
        $blocks = [
            \Bolt\Boltpay\Block\Minicart\Button::class =>
                PaypalConfig::METHOD_WPS_EXPRESS,
        ];
        foreach ($blocks as $blockInstanceName => $paymentMethodCode) {
//            if (!$this->paypalConfig->isMethodAvailable($paymentMethodCode)) {
//                continue;
//            }

            $params = [];
//            $params = [
//                'shortcutValidator' => $this->shortcutFactory->create($observer->getEvent()->getCheckoutSession()),
//            ];
//            if (!in_array('Bml', explode('\\', $blockInstanceName))) {
//                $params['checkoutSession'] = $observer->getEvent()->getCheckoutSession();
//            }


            // we believe it's \Magento\Framework\View\Element\Template
            $shortcut = $shortcutButtons->getLayout()->createBlock(
                $blockInstanceName,
                '',
                $params
            );

            if (!$observer->getEvent()->getIsCatalogProduct()) {
                $shortcut->setIsInCatalogProduct(
                    $observer->getEvent()->getIsCatalogProduct()
                )->setShowOrPosition(
                    $observer->getEvent()->getOrPosition()
                );

                $shortcutButtons->addShortcut($shortcut);
            }
        }
    }
}
