<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 02.08.19
 * Time: 20:44
 */

namespace Netzexpert\Odoo2MagentoAttributes\Plugin\Sales\Model\Order;

use Magento\Sales\Api\Data\InvoiceCommentCreationInterface;
use Magento\Sales\Api\Data\InvoiceCreationArgumentsInterface;
use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\InvoiceDocumentFactory;

class InvoiceDocumentFactoryPlugin
{
    /** @var InvoiceExtensionFactory  */
    private $invoiceExtensionFactory;

    public function __construct(
        InvoiceExtensionFactory $invoiceExtensionFactory
    ) {
        $this->invoiceExtensionFactory  = $invoiceExtensionFactory;
    }/** @noinspection PhpUnusedParameterInspection */
    /** @noinspection PhpUnusedParameterInspection */

    /**
     * @param InvoiceDocumentFactory $invoiceDocumentFactory
     * @param InvoiceInterface $invoice
     * @param OrderInterface $order
     * @param array $items
     * @param InvoiceCommentCreationInterface|null $comment
     * @param bool $appendComment
     * @param InvoiceCreationArgumentsInterface|null $arguments
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreate(
        InvoiceDocumentFactory $invoiceDocumentFactory,
        InvoiceInterface $invoice,
        OrderInterface $order,
        $items = [],
        InvoiceCommentCreationInterface $comment = null,
        $appendComment = false,
        InvoiceCreationArgumentsInterface $arguments = null
    ) {
        $invoiceExtensions = $invoice->getExtensionAttributes();
        if (!$invoiceExtensions) {
            $invoiceExtensions = $this->invoiceExtensionFactory->create();
        }
        $invoiceExtensions->setOdooCustomerId($arguments->getExtensionAttributes()->getOdooCustomerId());
        $invoiceExtensions->setOdooInvoiceNr($arguments->getExtensionAttributes()->getOdooInvoiceNr());
        return $invoice;
    }
}
