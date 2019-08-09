<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 09.08.19
 * Time: 14:40
 */

namespace Netzexpert\Odoo2MagentoAttributes\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface OrderItemManagementInterface
{
    /**
     * @param int $orderId
     * @return \Magento\Quote\Api\Data\CartItemInterface[]
     * @throws NoSuchEntityException
     */
    public function getOrderQuoteItems($orderId);
}
