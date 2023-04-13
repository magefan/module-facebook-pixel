<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Pixel;

use Magefan\FacebookPixel\Api\InitiateCheckoutInterface;
use Magefan\FacebookPixel\Block\AbstractPixel;
use Magefan\FacebookPixel\Model\Config;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Context;

class InitiateCheckout extends AbstractPixel
{
    public const INITIATE_CHECKOUT = 'InitiateCheckout';

    /**
     * @var InitiateCheckoutInterface
     */
    private $initiateCheckout;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * InitiateCheckout constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Json $json
     * @param CheckoutSession $checkoutSession
     * @param InitiateCheckoutInterface $initiateCheckout
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $json,
        CheckoutSession $checkoutSession,
        InitiateCheckoutInterface $initiateCheckout,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->initiateCheckout = $initiateCheckout;
        parent::__construct($context, $config, $json, $data);
    }

    /**
     * Get FB Pixel params
     *
     * @return array
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function getParameters(): array
    {
        $quote = $this->checkoutSession->getQuote();
        return $this->initiateCheckout->get($quote);
    }

    /**
     * Get event name
     *
     * @return string
     */
    protected function getEventName(): string
    {
        return self::INITIATE_CHECKOUT;
    }

    /**
     * @inheritDoc
     */
    protected function _toHtml(): string
    {
        $html = parent::_toHtml();

        $html .= '<script>
            window.mfFbPixelCheckout = ' . json_encode($this->getParameters()) . ';
        </script>';

        return $html;
    }
}
