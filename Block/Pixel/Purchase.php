<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Pixel;

use Magefan\FacebookPixel\Block\AbstractPixel;
use Magefan\FacebookPixel\Model\Config;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Context;
use Magefan\FacebookPixel\Api\PurchaseInterface;

class Purchase extends AbstractPixel
{
    public const PURCHASE = 'Purchase';

    /**
     * @var PurchaseInterface
     */
    private $purchase;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * Purchase constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Json $json
     * @param CheckoutSession $checkoutSession
     * @param PurchaseInterface $purchase
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $json,
        CheckoutSession $checkoutSession,
        PurchaseInterface $purchase,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->purchase = $purchase;
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
        $order = $this->checkoutSession->getLastRealOrder();
        return $this->purchase->get($order);
    }

    /**
     * Get event name
     *
     * @return string
     */
    protected function getEventName(): string
    {
        return self::PURCHASE;
    }
}
