<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 02.08.19
 * Time: 22:20
 */

namespace Netzexpert\Odoo2MagentoAttributes\Plugin\Sales\Api\Data;

use Magento\Sales\Api\Data\InvoiceInterface;

class InvoiceInterfacePlugin
{
    /**
     * @param InvoiceInterface $invoice
     * @param string|null $incrementId
     * @return string|null
     */
    public function afterGetIncrementId(
        InvoiceInterface $invoice,
        $incrementId
    ) {
        $incrementId = $invoice->getData('odoo_invoice_nr') ? $invoice->getData('odoo_invoice_nr') : $incrementId;
        return $incrementId;
    }
}
