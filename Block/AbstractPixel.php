<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;
use Magefan\FacebookPixel\Model\Config;
use Magento\Framework\Serialize\Serializer\Json;

abstract class AbstractPixel extends AbstractBlock
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Json
     */
    protected $json;

    /**
     * AbstractPixel constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $json,
        array $data = []
    ) {
        $this->config = $config;
        $this->json = $json;
        parent::__construct($context, $data);
    }

    /**
     * Get FB Pixel params
     *
     * @return array
     */
    abstract protected function getParameters(): array;

    /**
     * Get event name
     *
     * @return string
     */
    abstract protected function getEventName(): string;

    /**
     * Init FB pixel
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        if ($this->config->isEnabled()) {
            $parameters = $this->getParameters();
            $eventName = $this->getEventName();

            if ($parameters && $eventName) {
                return '<script>
                    fbq("' . $this->getTrackMethod() . '", '
                        . $this->json->serialize($eventName) . ', '
                        . $this->json->serialize($parameters)
                    . ')
                </script>';
            }
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getTrackMethod(): string
    {
        return "track";
    }
}
