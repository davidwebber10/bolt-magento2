<?php
/**
 *
 * Copyright © 2013-2017 Bolt, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Bolt\Boltpay\Controller\Cart;

use Bolt\Boltpay\Helper\Cart;
use Exception;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Bolt\Boltpay\Helper\Config as ConfigHelper;
use Bolt\Boltpay\Helper\Bugsnag;


/**
 * Class Data.
 * Create Bolt order controller.
 *
 * Called from the replace.phtml javascript block on checklout button click.
 *
 * @package Bolt\Boltpay\Controller\Cart
 */
class Data extends Action
{
	/**
	 * @var JsonFactory
	 */
	protected $resultJsonFactory;

	/**
	 * @var Cart
	 */
	protected $_cartHelper;

	/**
	 * @var ConfigHelper
	 */
	protected $_configHelper;

	/**
	 * @var Bugsnag
	 */
	protected $bugsnag;

	/**
	 * @param Context $context
	 * @param JsonFactory $resultJsonFactory
	 * @param Cart $cartHelper
	 * @param configHelper $configHelper
	 * @param Bugsnag $bugsnag
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct(
		Context      $context,
		JsonFactory  $resultJsonFactory,
		Cart         $cartHelper,
		configHelper $configHelper,
		Bugsnag      $bugsnag
	) {
		parent::__construct($context);
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_cartHelper       = $cartHelper;
		$this->_configHelper     = $configHelper;
		$this->bugsnag           = $bugsnag;
	}

	/**
	 * Get cart data for bolt pay ajax
	 *
	 * @return ResultInterface
	 * @throws Exception
	 */
	public function execute()
	{
		try {
			// flag to determinate the type of checkout / data sent to Bolt
			$payment_only        = $this->getRequest()->getParam('payment_only');
			// additional data collected from the (one page checkout) page,
			// i.e. billing address to be saved with the order
			$place_order_payload = $this->getRequest()->getParam('place_order_payload');
			// call the Bolt API
			$boltpayOrder = $this->_cartHelper->getBoltpayOrder( $payment_only, $place_order_payload );

			// format and send the response

			$cart = [
				'orderToken'  => $boltpayOrder->getResponse()->token,
				'authcapture' => $this->_configHelper->getAutomaticCaptureMode()
			];

			$hints = $this->_cartHelper->getHints($place_order_payload);

			$result = new DataObject();
			$result->setData('cart', $cart);
			$result->setData('hints', $hints);

		return $this->resultJsonFactory->create()->setData($result->getData());
		} catch ( Exception $e ) {
			$this->bugsnag->notifyException($e);
			throw $e;
		}
	}
}