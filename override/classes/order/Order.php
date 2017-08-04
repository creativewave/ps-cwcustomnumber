<?php

class Order extends OrderCore
{
    /**
     * Get last invoice number for the current month if monthly reset is active.
     * @author Guillaume Lebas
     *
     * @see OrderCore::getLastInvoiceNumber()
     */
    public static function getLastInvoiceNumber(): int
    {
        if (Configuration::get('PS_INVOICE_RESET_MONTHLY')) {
            return Db::getInstance()->getValue((new DbQuery())
                ->select('MAX(`number`)')
                ->from('order_invoice')
                ->where('DATE_FORMAT(`date_add`, "%Y%m") = '.date('Ym'))
            ) ?? 0;
        }

        return parent::getLastInvoiceNumber() ?? 0;
    }

    /**
     * Set last invoice number for the current month if monthly reset is active.
     * @author Guillaume Lebas
     *
     * @see OrderCore::setLastInvoiceNumber()
     */
    public static function setLastInvoiceNumber($id_order_invoice, $id_shop): bool
    {
        if (!Configuration::get('PS_INVOICE_RESET_MONTHLY', null, null, $id_shop)) {
            return parent::setLastInvoiceNumber($id_order_invoice, $id_shop);
        }

        // From here, it's the same logic as in the parent method...
        if (!$id_order_invoice) {
            return false;
        }

        $number = Configuration::get('PS_INVOICE_START_NUMBER', null, null, $id_shop);
        if ($number) {
            Configuration::updateValue('PS_INVOICE_START_NUMBER', false, false, null, $id_shop);
        }

        $sql = 'UPDATE `'._DB_PREFIX_.'order_invoice` SET number = ';
        if ($number) {
            $sql.= $number;
        } else {
            // ...except these 5 lines:
            $sql.= DB::getInstance()->getValue((new DbQuery)
                ->select('(MAX(`number`) + 1)')
                ->from('order_invoice')
                ->where('DATE_FORMAT(`date_add`, "%Y%m") = '.date('Ym'))
            );
        }
        $sql.= ' WHERE `id_order_invoice` = '.$id_order_invoice;

        return Db::getInstance()->execute($sql);
    }
}
