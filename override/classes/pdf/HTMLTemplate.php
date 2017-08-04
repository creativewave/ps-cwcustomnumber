<?php

abstract class HTMLTemplate extends HTMLTemplateCore
{
    /**
     * Get custom template if it exists.
     * @author Guillaume Lebas
     *
     * @see HTMLTemplateCore::getTemplate()
     */
    protected function getTemplate($template_name): string
    {
        $custom_template_name = $template_name.str_replace('order-slip', '', Configuration::get('PS_CREDIT_SLIP_MODEL'));
        $custom_template = parent::getTemplate($custom_template_name);

        if (file_exists($custom_template)) {
            return $custom_template;
        }

        return parent::getTemplate($template_name);
    }
}
