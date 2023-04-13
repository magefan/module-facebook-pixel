<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model;

use Magento\Config\Model\Config\Backend\Admin\Custom;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * General config
     */
    public const XML_PATH_EXTENSION_ENABLED = 'mffacebookpixel/general/enabled';
    public const XML_PATH_FB_PIXEL_ID = 'mffacebookpixel/general/fb_pixel_id';

    /**
     * Product attributes config
     */
    public const XML_PATH_ATTRIBUTES_PRODUCT = 'mffacebookpixel/attributes/product';

    /**
     * Customer data protection regulation config
     */
    public const XML_PATH_PROTECT_CUSTOMER_DATA = 'mffacebookpixel/customer_data/protect';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve true if module is enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isEnabled(string $storeId = null): bool
    {
        return (bool)$this->getConfig(self::XML_PATH_EXTENSION_ENABLED, $storeId) &&
            $this->getFbPixelId($storeId);
    }

    /**
     * Retrieve GTM account ID
     *
     * @param string|null $storeId
     * @return string
     */
    public function getFbPixelId(string $storeId = null): string
    {
        return (string)$this->getConfig(self::XML_PATH_FB_PIXEL_ID, $storeId);
    }

    /**
     * Retrieve Magento product attribute
     *
     * @param string|null $storeId
     * @return string
     */
    public function getProductAttribute(string $storeId = null): string
    {
        return (string)$this->getConfig(self::XML_PATH_ATTRIBUTES_PRODUCT, $storeId);
    }

    /**
     * Retrieve true if protect customer data is enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isProtectCustomerDataEnabled(string $storeId = null): bool
    {
        return (bool)$this->getConfig(self::XML_PATH_PROTECT_CUSTOMER_DATA, $storeId) &&
            $this->isCookieRestrictionModeEnabled($storeId);
    }

    /**
     * Retrieve true if cookie restriction mode enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isCookieRestrictionModeEnabled(string $storeId = null)
    {
        return (bool)$this->getConfig(Custom::XML_PATH_WEB_COOKIE_RESTRICTION, $storeId);
    }

    /**
     * Retrieve store config value
     *
     * @param string $path
     * @param string|null $storeId
     * @return mixed
     */
    public function getConfig(string $path, string $storeId = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
