<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Block\Adminhtml\System\Config\Form;

abstract class InfoPlan extends \Magefan\Community\Block\Adminhtml\System\Config\Form\Info
{
    /**
     * Get min plan name
     *
     * @return string
     */
    abstract protected function getMinPlan(): string;

    /**
     * Get section id
     *
     * @return string
     */
    abstract protected function getSectionId(): string;

    /**
     * Get section text
     *
     * @return string
     */
    abstract protected function getText(): string;

    /**
     * Return info block html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if ($this->getModuleVersion->execute($this->getModuleName() . $this->getMinPlan())) {
            return '';
        }

        $html = '';
        $html .= '<div style="padding:10px;background-color:#f8f8f8;border:1px solid #ddd;margin-bottom:7px;">';
        $html .= $this->getText();
        $html .= ' <a style="color: #ef672f; text-decoration: underline;" '
            . 'href="https://magefan.com/magento-2-google-tag-manager/pricing?utm_source=gtm_config'
            . '&utm_medium=link&utm_campaign=regular" target="_blank">'
            . 'Read more</a>.';
        $html .= '</div>';

        $script = '
                require(["jquery", "Magento_Ui/js/modal/alert", "domReady!"], function($, alert){
                    setInterval(function(){
                        var $plusSection = $("#' . $this->getSectionId() . '-state").parent(".section-config");
                        $plusSection.find(".use-default").css("visibility", "hidden");
                        $plusSection.find("input,select").each(function(){
                            $(this).attr("readonly", "readonly");
                            $(this).removeAttr("disabled");
                            if ($(this).data("fpdisabled")) return;
                            $(this).data("fpdisabled", 1);
                            $(this).click(function(){
                                alert({
                                    title: "You cannot change this option.",
                                    content: "' .
                                    (
                                        ($this->getMinPlan() == 'Extra')
                                        ? 'This option is available in <strong>Extra</strong> plan only.'
                                        : 'This option is available in <strong>Plus or Extra</strong> plans only.'
                                    )
                                    . '",
                                    buttons: [{
                                        text: "Upgrade Plan Now",
                                        class: "action primary accept",
                                        click: function () {
                                            var url = "https://magefan.com/magento-2-google-tag-manager/pricing?"
                                                    + "utm_source=gtm_config&utm_medium=link&utm_campaign=regular";
                                            window.open(url);
                                        }
                                    }]
                                });
                            });
                        });
                    }, 1000);
                });
            ';

        $html .= $this->mfSecureRenderer->renderTag('script', [], $script, false);

        return $html;
    }
}
