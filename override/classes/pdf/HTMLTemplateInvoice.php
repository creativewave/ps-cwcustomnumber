<?php

class HTMLTemplateInvoice extends HTMLTemplateInvoiceCore
{
    /**
     * Get month in formatted file name if monthly reset is active.
     * @author Guillaume Lebas
     *
     * @see HTMLTemplateInvoiceCore::getFilename()
     */
    public function getFilename(): string
    {
        if (Configuration::get('PS_INVOICE_USE_MONTH', null, null, $this->order->id_shop)) {
            if (Configuration::get('PS_INVOICE_YEAR_POS', null, null, $this->order->id_shop)) {
                return sprintf(
                    Configuration::get('PS_INVOICE_MONTH_POS', null, null, $this->order->id_shop) ? '%1$s%4$s-%3$s-%2$06d' : '%1$s%3$s-%4$s-%2$06d',
                    Configuration::get('PS_INVOICE_PREFIX', Context::getContext()->language->id, null, $this->order->id_shop),
                    $this->order_invoice->number,
                    date('Y', strtotime($this->order_invoice->date_add)),
                    date('m', strtotime($this->order_invoice->date_add))
                ).'.pdf';
            } else {
                return sprintf(
                    Configuration::get('PS_INVOICE_MONTH_POS', null, null, $this->order->id_shop) ? '%1$s%2$06d-%4$s-%3$s' : '%1$s%2$06d-%3$s-%4$s',
                    Configuration::get('PS_INVOICE_PREFIX', Context::getContext()->language->id, null, $this->order->id_shop),
                    $this->order_invoice->number,
                    date('Y', strtotime($this->order_invoice->date_add)),
                    date('m', strtotime($this->order_invoice->date_add))
                ).'.pdf';
            }
        }

        return parent::getFilename();
    }
}
