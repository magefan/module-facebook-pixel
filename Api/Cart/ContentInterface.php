<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Api\Cart;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;

interface ContentInterface
{
    /**
     * Get cart content
     *
     * @param Item $quoteItem
     * @return array
     * @throws NoSuchEntityException
     */
    public function get(Item $quoteItem): array;
}
