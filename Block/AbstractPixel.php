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
use Magefan\Community\Api\SecureHtmlRendererInterface;

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
     * @var SecureHtmlRendererInterface
     */
    protected $mfSecureRenderer;

    /**
     * AbstractPixel constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Json $json
     * @param array $data
     * @param SecureHtmlRendererInterface|null $mfSecureRenderer
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $json,
        array $data = [],
        ?SecureHtmlRendererInterface $mfSecureRenderer = null
    ) {
        $this->config = $config;
        $this->json = $json;
        $this->mfSecureRenderer = $mfSecureRenderer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(SecureHtmlRendererInterface::class);
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
        if ($this->config->isEnabled() && $this->config->getFbPixelId()) {
            $parameters = $this->getParameters();
            $eventName = $this->getEventName();
            if ($parameters && $eventName) {
                $script = '
                    fbq("' . $this->getTrackMethod() . '", '
                        . $this->json->serialize($eventName) . ', '
                        . $this->json->serialize($parameters) . ', '
                        . '{ "eventID": "' . $eventName
                        . '" + "." + Math.floor(Math.random() * 1000000) + "."'
                        . ' + Date.now(), "event_source_url": window.location.href, "referrer_url": document.referrer }'
                    . ');
                ';

                return $this->mfSecureRenderer->renderTag('script', ['style' => 'display:none'], $script, false);
            }
        }

        return '';
    }

    /**
     * Get track method
     *
     * @return string
     */
    protected function getTrackMethod(): string
    {
        return "track";
    }
}
