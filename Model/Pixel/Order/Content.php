<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model\Pixel\Order;

use Magefan\FacebookPixel\Api\Order\ContentInterface;
use Magefan\FacebookPixel\Model\AbstractPixel;
use Magento\Sales\Api\Data\OrderItemInterface;

class Content extends AbstractPixel implements ContentInterface
{
    /**
     * @inheritDoc
     */
    public function get(OrderItemInterface $orderItem): array
    {
        $product = $orderItem->getProduct();
        return [
            'id' => $product->getData($this->config->getProductAttribute()),
            'quantity' => $orderItem->getQtyOrdered() * 1
        ];
    }
}
