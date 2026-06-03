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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Content extends AbstractPixel implements ContentInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @inheritDoc
     */
    public function get(Item $quoteItem): array
    {
        $product = $this->getItemProduct($quoteItem);
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
     * Get product from quote item
     *
     * @param Item $quoteItem
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    protected function getItemProduct(Item $quoteItem)
    {
        $product = $quoteItem->getProduct();
        if ('configurable' === $product->getTypeId()) {
            $options = $quoteItem->getOptions();
            if ($options) {
                foreach ($options as $option) {
                    if ($option->getCode() === 'simple_product') {
                        try {
                            $product = $this->getProductRepository()->getById($option->getProductId());
                            break;
                        // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
                        } catch (NoSuchEntityException $e) {

                        }
                    }
                }
            }
        }
        return $product;
    }

    /**
     * Get product repository instance
     *
     * @return ProductRepositoryInterface
     */
    protected function getProductRepository(): ProductRepositoryInterface
    {
        if (null === $this->productRepository) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->productRepository = $objectManager->get(ProductRepositoryInterface::class);
        }
        return $this->productRepository ;
    }
}
