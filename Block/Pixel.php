<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magefan\FacebookPixel\Model\Config;
use Magefan\FacebookPixel\Model\CustomerData;
use Magento\Framework\View\Element\Template\Context;

class Pixel extends Template
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var CustomerData
     */
    private $customerData;

    /**
     * Pixel constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param CustomerData $customerData
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        CustomerData $customerData,
        array $data = []
    ) {
        $this->config = $config;
        $this->customerData = $customerData;
        parent::__construct($context, $data);
    }

    /**
     * Get FB pixel ID
     *
     * @return string
     */
    public function getFbPixelId(): string
    {
        return $this->config->getFbPixelId();
    }

    /**
     * Check if protect customer data is enabled
     *
     * @return bool
     */
    public function isProtectCustomerDataEnabled(): bool
    {
        return $this->config->isProtectCustomerDataEnabled();
    }

    /**
     * Retrieve true if speed optimization is enabled
     *
     * @return bool
     */
    public function isSpeedOptimizationEnabled(): bool
    {
        return (bool)$this->config->isSpeedOptimizationEnabled();
    }

    /**
     * Get current website ID
     *
     * @return int
     * @throws NoSuchEntityException
     */
    public function getWebsiteId(): int
    {
        return (int)$this->_storeManager->getStore()->getWebsiteId();
    }

    /**
     * Init FB pixel
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        if ($this->config->isEnabled()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return false|string
     */
    public function getCustomerData()
    {
        return json_encode($this->customerData->getCustomerData());
    }
}
