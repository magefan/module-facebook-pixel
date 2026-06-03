<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Pixel;

use Magefan\FacebookPixel\Block\AbstractPixel;

class Other extends AbstractPixel
{
    /**
     * Get FB Pixel params
     *
     * @return array
     */
    protected function getParameters(): array
    {
        return [];
    }

    /**
     * Get event name
     *
     * @return string
     */
    protected function getEventName(): string
    {
        return '';
    }
}
