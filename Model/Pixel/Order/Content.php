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
        $product = $this->getItemProduct($orderItem);
        return [
            'id' => $product->getData($this->config->getProductAttribute()),
            'quantity' => $orderItem->getQtyOrdered() * 1
        ];
    }

    /**
     * Get product from order item
     *
     * @param OrderItemInterface $orderItem
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    protected function getItemProduct(OrderItemInterface $orderItem)
    {
        $product = $orderItem->getProduct();
        if ('configurable' === $product->getTypeId()) {
            if ($childItem = $orderItem->getMfChildrenItem()) {
                $product =  $childItem->getProduct();
            }
        }
        return $product;
    }
}
