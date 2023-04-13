<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model\Pixel\Product;

use Magefan\FacebookPixel\Api\Product\ContentInterface;
use Magefan\FacebookPixel\Model\AbstractPixel;
use Magento\Catalog\Model\Product;

class Content extends AbstractPixel implements ContentInterface
{
    /**
     * @inheritDoc
     */
    public function get(Product $product): array
    {
        return [
            'id' => $product->getData($this->config->getProductAttribute()),
            'quantity' => 1
        ];
    }
}
