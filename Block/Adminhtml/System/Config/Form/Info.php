<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\FacebookPixel\Block\Adminhtml\System\Config\Form;

class Info extends \Magefan\Community\Block\Adminhtml\System\Config\Form\Info
{
    /**
     * Return info block html
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '';
        $html .= '<div style="padding:10px;background-color:#f8f8f8;border:1px solid #ddd;margin-bottom:7px;">
            To setup <strong>Facebook Pixel</strong> please navigate to
            <a href="' . $this->escapeHtml($this->getUrl('fbeadmin/setup')) . '" target="_blank"> Stores > Facebook > Setup</a>.
        </div>';

        $html .= '<div style="padding:10px;background-color:#f8f8f8;border:1px solid #ddd;margin-bottom:7px;">
            For the <strong>Facebook Pixel</strong> configuration please navigate to
            <a href="' . $this->escapeHtml($this->getUrl('*/*/*', ['section' => 'facebook_business_extension'])) . '" target="_blank">Stores > Configuration > Facebook > Business Extension</a>.
        </div>';

        return $html;
    }
}
