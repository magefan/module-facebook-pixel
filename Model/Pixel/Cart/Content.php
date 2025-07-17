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
        $product = $this->getProduct($quoteItem);
        return [
            /*
            'id' => ($this->config->getProductAttribute() == 'sku')
                ? $quoteItem->getSku()
                : $product->getData($this->config->getProductAttribute()),
            */
            'id' => $product->getData($this->config->getProductAttribute()),
            'quantity' => $quoteItem->getQty() * 1
        ];
    }

    /**
     * @param $quoteItem
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    protected function getProduct($quoteItem)
    {
        $product = $quoteItem->getProduct();
        if ($product->getTypeId() === 'configurable') {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productRepository = $objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
            $options = $quoteItem->getOptions();
            foreach ($options as $option) {
                if ($option->getCode() === 'simple_product') {
                    try {
                        $product = $productRepository->getById($option->getProductId());
                    } catch (\Exception $e) {

                    }
                }
            }
        }
        return $product;
    }
}
