<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;

interface InitiateCheckoutInterface
{
    /**
     * Get FB pixel data
     *
     * @param Quote $quote
     * @return array
     * @throws NoSuchEntityException
     */
    public function get(Quote $quote): array;
}
