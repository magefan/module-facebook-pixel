<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Api\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderItemInterface;

interface ContentInterface
{
    /**
     * Get order content
     *
     * @param OrderItemInterface $orderItem
     * @return array
     * @throws NoSuchEntityException
     */
    public function get(OrderItemInterface $orderItem): array;
}
