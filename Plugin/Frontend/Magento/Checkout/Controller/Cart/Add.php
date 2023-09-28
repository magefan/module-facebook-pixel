<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Plugin\Frontend\Magento\Checkout\Controller\Cart;

use Magento\Checkout\Controller\Cart\Add as Subject;
use Magento\Framework\App\RequestInterface;
use Magefan\FacebookPixel\Model\Config;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Checkout\Model\Session;

class Add
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Config $config
     * @param RequestInterface $request
     * @param Session|null $session
     */
    public function __construct(
        Config $config,
        RequestInterface $request,
        Session $session = null
    )
    {
        $this->config = $config;
        $this->request = $request;
        $this->session = $session ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->create(Session::class);
    }


    /**
     * @param Subject $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(
        Subject $subject,
                $result
    ) {
        if ($this->config->isEnabled()
            && !$this->request->isAjax()
            && $result instanceof Redirect) {
            $this->session->setMfFacebookPixelAddToCart(true);
        }

        return $result;
    }
}