<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model\Pixel;

use Magefan\FacebookPixel\Api\InitiateCheckoutInterface;
use Magefan\FacebookPixel\Model\AbstractPixel;
use Magefan\FacebookPixel\Model\Config;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magefan\FacebookPixel\Api\Cart\ContentInterface;
use Magento\Store\Model\StoreManagerInterface;

class InitiateCheckout extends AbstractPixel implements InitiateCheckoutInterface
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * InitiateCheckout constructor.
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
    public function get(Quote $quote): array
    {
        $contents = [];
        $numItems = 0;

        foreach ($quote->getAllVisibleItems() as $item) {
            $contents[] = $this->content->get($item);
            $numItems += $item->getQty() * 1;
        }

        $contentIds = [];
        foreach ($contents as $content) {
            $contentIds[] = $content['id'];
        }

        return [
            'content_ids' => $contentIds,
            'content_name' => 'Checkout',
            'contents' => $contents,
            'currency' => $this->getCurrentCurrencyCode(),
            'num_items' => $numItems,
            'value' => $this->formatPrice((float)$quote->getGrandTotal())
        ];
    }
}
