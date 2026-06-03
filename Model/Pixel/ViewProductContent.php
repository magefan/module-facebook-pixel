<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model\Pixel;

use Magefan\FacebookPixel\Api\ViewProductContentInterface;
use Magefan\FacebookPixel\Model\AbstractPixel;
use Magefan\FacebookPixel\Model\Config;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magefan\FacebookPixel\Api\Product\ContentInterface;
use Magento\Store\Model\StoreManagerInterface;

class ViewProductContent extends AbstractPixel implements ViewProductContentInterface
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * ViewProductContent constructor.
     *
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ContentInterface $content
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        ContentInterface $content
    ) {
        $this->content = $content;
        parent::__construct($config, $storeManager, $categoryRepository);
    }

    /**
     * @inheritDoc
     */
    public function get(Product $product): array
    {
        $categoryNames = $this->getCategoryNames($product);
        $content = $this->content->get($product);

        return [
            'content_ids' => [$content['id']],
            'content_category' => implode('/', $categoryNames),
            'content_name' => $product->getName(),
            'content_type' => 'product',
            'contents' => [
                $content
            ],
            'currency' => $this->getCurrentCurrencyCode(),
            'value' => $this->getPrice($product)
        ];
    }
}
