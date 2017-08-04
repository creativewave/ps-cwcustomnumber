<?php

class OrderInvoice extends OrderInvoiceCore
{
    /**
     * Get formatted number of invoice with month if monthly reset is active.
     * @author Guillaume Lebas
     *
     * @see OrderInvoiceCore::getInvoiceNumberFormatted()
     */
    public function getInvoiceNumberFormatted($id_lang, $id_shop = null): string
    {
        if (Configuration::get('PS_INVOICE_USE_MONTH', null, null, $id_shop)) {
            if (Configuration::get('PS_INVOICE_YEAR_POS', null, null, $id_shop)) {
                return sprintf(
                    Configuration::get('PS_INVOICE_MONTH_POS', null, null, $id_shop) ? '%1$s%4$s-%3$s/%2$06d' : '%1$s%3$s-%4$s/%2$06d',
                    Configuration::get('PS_INVOICE_PREFIX', $id_lang, null, $id_shop),
                    $this->number,
                    date('Y', strtotime($this->date_add)),
                    date('m', strtotime($this->date_add))
                );
            }
            return sprintf(
                Configuration::get('PS_INVOICE_MONTH_POS', null, null, $id_shop) ? '%1$s%2$06d/%4$s-%3$s' : '%1$s%2$06d/%3$s-%4$s',
                Configuration::get('PS_INVOICE_PREFIX', $id_lang, null, $id_shop),
                $this->number,
                date('Y', strtotime($this->date_add)),
                date('m', strtotime($this->date_add))
            );
        }

        return parent::getInvoiceNumberFormatted($id_lang, $id_shop);
    }
}
