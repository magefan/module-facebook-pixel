<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Pixel;

use Magefan\FacebookPixel\Api\ViewProductContentInterface;
use Magefan\FacebookPixel\Block\AbstractPixel;
use Magefan\FacebookPixel\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Context;

class ViewProductContent extends AbstractPixel
{
    public const VIEW_CONTENT = 'ViewContent';

    /**
     * @var ViewProductContentInterface
     */
    private $viewProductContent;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * ViewProductContent constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Json $json
     * @param Registry $registry
     * @param ViewProductContentInterface $viewProductContent
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $json,
        Registry $registry,
        ViewProductContentInterface $viewProductContent,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->viewProductContent = $viewProductContent;
        parent::__construct($context, $config, $json, $data);
    }

    /**
     * Get FB Pixel params
     *
     * @return array
     * @throws NoSuchEntityException
     */
    protected function getParameters(): array
    {
        return $this->viewProductContent->get($this->getCurrentProduct());
    }

    /**
     * Get event name
     *
     * @return string
     */
    protected function getEventName(): string
    {
        return self::VIEW_CONTENT;
    }

    /**
     * Get current product
     *
     * @return Product
     */
    private function getCurrentProduct(): Product
    {
        return $this->registry->registry('current_product');
    }
}
