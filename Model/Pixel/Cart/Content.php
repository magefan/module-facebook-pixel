<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model\Pixel\Cart;

use Magefan\FacebookPixel\Api\Cart\ContentInterface;
use Magefan\FacebookPixel\Model\AbstractPixel;
use Magento\Quote\Model\Quote\Item;

class Content extends AbstractPixel implements ContentInterface
{
    /**
     * @inheritDoc
     */
    public function get(Item $quoteItem): array
    {
        $product = $quoteItem->getProduct();
        return [
            'id' => ($this->config->getProductAttribute() == 'sku')
                ? $quoteItem->getSku()
                : $product->getData($this->config->getProductAttribute()),
            'quantity' => $quoteItem->getQty() * 1
        ];
    }
}
