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
use Magento\Customer\Model\Session as CustomerSession;

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
    public const XML_PATH_ATTRIBUTES_CATEGORIES = 'mffacebookpixel/attributes/categories';

    /**
     * Speed optimization config
     */
    public const XML_PATH_SPEED_OPTIMIZATION_ENABLED = 'mffacebookpixel/page_speed_optimization/enabled';

    /**
     * Customer data protection regulation config
     */
    public const XML_PATH_PROTECT_CUSTOMER_DATA = 'mffacebookpixel/customer_data/protect';

    /**
     * Display Product Price For customer groups
     */
    public const XML_PATH_DISPLAY_PRODUCT_PRICE_FOR = 'mffacebookpixel/attributes/display_product_price_for';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CustomerSession|null
     */
    private $customerSession;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerSession|null $customerSession
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ?CustomerSession $customerSession = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
    }

    /**
     * @return bool
     */
    public function isCustomerGroupAllowedToSeeProductPrice(): bool
    {
        $allowedGroups = $this->getConfig(self::XML_PATH_DISPLAY_PRODUCT_PRICE_FOR);

        if (!$allowedGroups) {
            return false;
        }

        $allowedGroups = explode(',', $allowedGroups);

        $session = $this->customerSession ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(CustomerSession::class);
        $customerGroupId = (string)$session->getCustomerGroupId();

        return in_array('all', $allowedGroups) || in_array($customerGroupId, $allowedGroups);
    }

    /**
     * Retrieve true if module is enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isEnabled(?string $storeId = null): bool
    {
        return (bool)$this->getConfig(self::XML_PATH_EXTENSION_ENABLED, $storeId);
    }

    /**
     * Retrieve GTM account ID
     *
     * @param string|null $storeId
     * @return string
     */
    public function getFbPixelId(?string $storeId = null): string
    {
        return trim((string)$this->getConfig(self::XML_PATH_FB_PIXEL_ID, $storeId));
    }

    /**
     * Retrieve Magento product attribute
     *
     * @param string|null $storeId
     * @return string
     */
    public function getProductAttribute(?string $storeId = null): string
    {
        return trim((string)$this->getConfig(self::XML_PATH_ATTRIBUTES_PRODUCT, $storeId));
    }

    /**
     * Retrieve Magento category attribute
     *
     * @param string|null $storeId
     * @return string
     */
    public function getCategoriesAttribute(?string $storeId = null): string
    {
        return trim((string)$this->getConfig(self::XML_PATH_ATTRIBUTES_CATEGORIES, $storeId));
    }

    /**
     * Retrieve true if speed optimization is enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isSpeedOptimizationEnabled(?string $storeId = null): bool
    {
        return (bool)$this->getConfig(self::XML_PATH_SPEED_OPTIMIZATION_ENABLED, $storeId);
    }

    /**
     * Retrieve true if protect customer data is enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isProtectCustomerDataEnabled(?string $storeId = null): bool
    {
        return (bool)$this->getConfig(self::XML_PATH_PROTECT_CUSTOMER_DATA, $storeId);
    }

    /**
     * Retrieve true if cookie restriction mode enabled
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isCookieRestrictionModeEnabled(?string $storeId = null): bool
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
    public function getConfig(string $path, ?string $storeId = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
