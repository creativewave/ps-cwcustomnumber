<?php

class HTMLTemplateOrderSlip extends HTMLTemplateOrderSlipCore
{
    /**
     * Register title with the formatted order slip number.
     * @author Guillaume Lebas
     *
     * @see HTMLTemplateOrderSlipCore::__construct()
     */
    public function __construct(OrderSlip $order_slip, $smarty)
    {
        parent::__construct($order_slip, $smarty);
        $this->title = $order_slip->getOrderSlipNumberFormatted(Context::getContext()->language->id, $this->shop->id);
    }

    /**
     * Get filename.
     * @see HTMLTemplateInvoice::getFilename()
     * @author Guillaume Lebas
     *
     * @see HTMLTemplateOrderSlipCore::getFilename()
     */
    public function getFilename(): string
    {
        if (Configuration::get('PS_CREDIT_SLIP_USE_MONTH', null, null, $this->order->id_shop)) {
            if (Configuration::get('PS_CREDIT_SLIP_YEAR_POS', null, null, $this->order->id_shop)) {
                return sprintf(
                    Configuration::get('PS_CREDIT_SLIP_MONTH_POS', null, null, $this->order->id_shop) ? '%1$s%4$s-%3$s-%2$06d' : '%1$s%3$s-%4$s-%2$06d',
                    Configuration::get('PS_CREDIT_SLIP_PREFIX', Context::getContext()->language->id, null, $this->order->id_shop),
                    $this->order_slip->number,
                    date('Y', strtotime($this->order_slip->date_add)),
                    date('m', strtotime($this->order_slip->date_add))
                ).'.pdf';
            } else {
                return sprintf(
                    Configuration::get('PS_CREDIT_SLIP_MONTH_POS', null, null, $this->order->id_shop) ? '%1$s%2$06d-%4$s-%3$s' : '%1$s%2$06d-%3$s-%4$s',
                    Configuration::get('PS_CREDIT_SLIP_PREFIX', Context::getContext()->language->id, null, $this->order->id_shop),
                    $this->order_slip->number,
                    date('Y', strtotime($this->order_slip->date_add)),
                    date('m', strtotime($this->order_slip->date_add))
                ).'.pdf';
            }
        } elseif (Configuration::get('PS_CREDIT_SLIP_USE_YEAR', null, null, $this->order->id_shop)) {
            return sprintf(
                Configuration::get('PS_CREDIT_SLIP_YEAR_POS') ? '%1$s%3$s-%2$06d' : '%1$s%2$06d-%3$s',
                Configuration::get('PS_CREDIT_SLIP_PREFIX', $id_lang, null, $id_shop),
                $this->order_slip->number,
                date('Y', strtotime($this->order_slip->date_add))
            ).'.pdf';
        }

        return sprintf(
            '%1$s%2$06d',
            Configuration::get('PS_CREDIT_SLIP_PREFIX', $id_lang, null, $id_shop),
            $this->order_slip->number
        ).'.pdf';
    }
}
