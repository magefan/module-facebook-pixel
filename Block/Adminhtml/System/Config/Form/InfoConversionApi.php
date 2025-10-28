<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Adminhtml\System\Config\Form;

class InfoConversionApi extends InfoPlan
{

    /**
     * Get min plan name
     *
     * @return string
     */
    protected function getMinPlan(): string
    {
        return 'Extra';
    }

    /**
     * Get section id
     *
     * @return string
     */
    protected function getSectionId(): string
    {
        return 'mffacebookpixel_conversion_api';
    }

    /**
     * Get section text
     *
     * @return string
     */
    protected function getText(): string
    {
        return 'Conversion API is available in <strong>Extra</strong> plans only.';
    }
}
