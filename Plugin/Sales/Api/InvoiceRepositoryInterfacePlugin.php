<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 02.08.19
 * Time: 19:16
 */

namespace Netzexpert\Odoo2MagentoAttributes\Plugin\Sales\Api;

use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;

class InvoiceRepositoryInterfacePlugin
{
    /** @var InvoiceExtensionFactory  */
    private $invoiceExtensionFactory;

    /**
     * InvoiceRepositoryInterfacePlugin constructor.
     * @param InvoiceExtensionFactory $invoiceExtensionFactory
     */
    public function __construct(
        InvoiceExtensionFactory $invoiceExtensionFactory
    ) {
        $this->invoiceExtensionFactory  = $invoiceExtensionFactory;
    }

    /**
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        InvoiceRepositoryInterface $invoiceRepository,
        InvoiceInterface $invoice
    ) {
        $invoiceExtensions = $invoice->getExtensionAttributes();
        if (!$invoiceExtensions) {
            $invoiceExtensions = $this->invoiceExtensionFactory->create();
        }
        $invoiceExtensions->setOdooCustomerId($invoice->getData('odoo_customer_id'));
        $invoiceExtensions->setOdooInvoiceNr($invoice->getData('odoo_invoice_nr'));
        $invoice->setIncrementId($invoice->getData('odoo_invoice_nr'));
        return $invoice;
    }

    /**
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param InvoiceInterface $invoice
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(
        InvoiceRepositoryInterface $invoiceRepository,
        InvoiceInterface $invoice
    ) {
        $invoiceExtensions = $invoice->getExtensionAttributes();
        $invoice->setData('odoo_customer_id', $invoiceExtensions->getOdooCustomerId());
        $invoice->setData('odoo_invoice_nr', $invoiceExtensions->getOdooInvoiceNr());
        return [$invoice];
    }
}
