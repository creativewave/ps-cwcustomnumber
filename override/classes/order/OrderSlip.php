<?php

class OrderSlip extends OrderSlipCore
{
    /**
     * Added object model's property.
     * @author Guillaume Lebas
     * @var int
     */
    public $number;

    /**
     * Define new model properties before object model is instanciated.
     * @author Guillaume Lebas
     *
     * @see OrderSlipCore::__construct()
     */
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        static::$definition['fields']['number'] = [
            'type' => static::TYPE_INT, 'validate' => 'isInt',
        ];
        parent::__construct($id, $id_lang, $id_shop);
    }

    /**
     * Create order slip with a progressive number.
     * @see OrderCore::setInvoice()
     * @author Guillaume Lebas
     *
     * @see OrderSlipCore::create()
     */
    public static function create(
        Order $order,
        $products,
        $shipping_cost = false,
        $amount = 0,
        $amount_choosen = false,
        $add_tax = true
    ): bool
    {
        if (!parent::create($order, $products, $shipping_cost, $amount, $amount_choosen, $add_tax)) {
            return false;
        }
        $id_order_slip = Db::getInstance()->getValue((new DbQuery())
            ->select('id_order_slip')
            ->from('order_slip')
            ->where("id_order = $order->id")
        );

        return static::setLastOrderSlipNumber($id_order_slip, $order->id_shop);
    }

    /**
     * Set last order slip number.
     * @see OrderCore::setLastInvoiceNumber()
     * @author Guillaume Lebas
     */
    public static function setLastOrderSlipNumber(int $id_order_slip, int $id_shop): bool
    {
        $db_query = (new DbQuery)->select('(MAX(`number`) + 1)')->from('order_slip');
        if (Configuration::get('PS_CREDIT_SLIP_RESET_MONTHLY', null, null, $id_shop)) {
            $db_query->where('DATE_FORMAT(`date_add`, "%Y%m") = '.date('Ym'));
        } elseif (Configuration::get('PS_CREDIT_SLIP_RESET', null, null, $id_shop)) {
            $db_query->where('DATE_FORMAT(`date_add`, "%Y") = '.date('Y'));
        }
        $new_number = Db::getInstance()->getValue($db_query);

        return Db::getInstance()->update(
            'order_slip', ['number' => $new_number], 'id_order_slip = '.$id_order_slip
        );
    }

    /**
     * Get formatted order slip number.
     * @see OrderInvoiceCore::getInvoiceNumberFormatted()
     * @author Guillaume Lebas
     */
    public function getOrderSlipNumberFormatted(int $id_lang, int $id_shop = null): string
    {
        $format = '%1$s%2$06d';

        if (Configuration::get('PS_CREDIT_SLIP_USE_MONTH', null, null, $id_shop)) {
            if (Configuration::get('PS_CREDIT_SLIP_YEAR_POS', null, null, $id_shop)) {
                return sprintf(
                    Configuration::get('PS_CREDIT_SLIP_MONTH_POS', null, null, $id_shop) ? '%1$s%4$s-%3$s/%2$06d' : '%1$s%3$s-%4$s/%2$06d',
                    Configuration::get('PS_CREDIT_SLIP_PREFIX', $id_lang, null, $id_shop),
                    $this->number,
                    date('Y', strtotime($this->date_add)),
                    date('m', strtotime($this->date_add))
                );
            } else {
                return sprintf(
                    Configuration::get('PS_CREDIT_SLIP_MONTH_POS', null, null, $id_shop) ? '%1$s%2$06d/%4$s-%3$s' : '%1$s%2$06d/%3$s-%4$s',
                    Configuration::get('PS_CREDIT_SLIP_PREFIX', $id_lang, null, $id_shop),
                    $this->number,
                    date('Y', strtotime($this->date_add)),
                    date('m', strtotime($this->date_add))
                );
            }
        }

        return sprintf(
            Configuration::get('PS_CREDIT_SLIP_YEAR_POS') ? '%1$s%3$s/%2$06d' : '%1$s%2$06d/%3$s',
            Configuration::get('PS_CREDIT_SLIP_PREFIX', $id_lang, null, $id_shop),
            $this->number,
            date('Y', strtotime($this->date_add))
        );
    }
}
