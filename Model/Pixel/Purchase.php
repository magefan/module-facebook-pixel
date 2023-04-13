<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model\Pixel;

use Magefan\FacebookPixel\Api\PurchaseInterface;
use Magefan\FacebookPixel\Model\AbstractPixel;
use Magefan\FacebookPixel\Model\Config;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Sales\Model\Order;
use Magefan\FacebookPixel\Api\Order\ContentInterface;
use Magento\Store\Model\StoreManagerInterface;

class Purchase extends AbstractPixel implements PurchaseInterface
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * Purchase constructor.
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
    public function get(Order $order): array
    {
        if ($order) {
            $contents = [];
            $numItems = 0;

            foreach ($order->getAllVisibleItems() as $item) {
                $contents[] = $this->content->get($item);
                $numItems += $item->getQtyOrdered() * 1;
            }

            $contentIds = [];
            foreach ($contents as $content) {
                $contentIds[] = $content['id'];
            }

            return [
                'content_ids' => $contentIds,
                'content_name' => 'Purchase',
                'content_type' => 'product',
                'contents' => $contents,
                'currency' => $this->getCurrentCurrencyCode(),
                'num_items' => $numItems,
                'value' => $this->formatPrice((float)$order->getGrandTotal())
            ];
        }

        return [];
    }
}
