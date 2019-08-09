<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 09.08.19
 * Time: 15:41
 */

namespace Netzexpert\Odoo2MagentoAttributes\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory as QuoteItemCollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Netzexpert\Odoo2MagentoAttributes\Api\OrderItemManagementInterface;

class OrderItemManagement implements OrderItemManagementInterface
{
    /** @var OrderRepositoryInterface  */
    private $orderRepository;

    /** @var QuoteItemCollectionFactory  */
    private $quoteItemCollectionFactory;

    private $cartItemExtensionFactory;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        QuoteItemCollectionFactory $quoteItemCollectionFactory,
        CartItemExtensionFactory $cartItemExtensionFactory
    ) {
        $this->orderRepository              = $orderRepository;
        $this->quoteItemCollectionFactory   = $quoteItemCollectionFactory;
        $this->cartItemExtensionFactory     = $cartItemExtensionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getOrderQuoteItems($orderId)
    {
        try {
            $order = $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException $exception) {
            throw NoSuchEntityException::singleField('orderId', $orderId);
        }
        $itemIds = array_keys($order->getItems());

        $quoteItems = $this->quoteItemCollectionFactory->create();
        $quoteItems->addFieldToFilter('item_id', ['in' => implode(',', $itemIds)]);
        $quoteItemsArray = [];
        /** @var Item $quoteItem */
        foreach ($quoteItems as $quoteItem) {
            $options = $quoteItem->getOptions();
            $product = $quoteItem->getProduct();
            $product->setOptions($options);
            $orderOptions = $product->getTypeInstance()->getOrderOptions($product);
            if (!empty($orderOptions['configurator_options'])) {
                $itemExtension = $quoteItem->getExtensionAttributes();
                if (!$itemExtension) {
                    $itemExtension = $this->cartItemExtensionFactory->create();
                }
                $itemExtension->setConfiguratorOptions($orderOptions['configurator_options']);
                $quoteItem->setExtensionAttributes($itemExtension);
            }
            $quoteItemsArray[] = $quoteItem;
        }
        return $quoteItemsArray;
    }

}
