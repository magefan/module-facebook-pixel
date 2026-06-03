<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Pixel;

class PurchaseGetOrderFromRequest extends Purchase
{
    /**
     * Get order
     *
     * @return \Magento\Sales\Model\Order|null
     */
    protected function getOrder()
    {
        $order = $this->getOrderFactory()->create()->loadByIncrementId($this->getOrderId());

        if (!$order->getId()) {
            return null;
        }
        return $order;
    }

    /**
     * Get order factory
     *
     * @return mixed
     */
    protected function getOrderFactory()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager->get(\Magento\Sales\Model\OrderFactory::class);
    }

    /**
     * Get order id
     *
     * @return string
     */
    protected function getOrderId()
    {
        $request = $this->getRequest();

        if ($request) {
            return (string)$request->getParam('order_id');
        }

        return 0;
    }
}
