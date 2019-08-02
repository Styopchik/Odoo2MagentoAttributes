<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 02.08.19
 * Time: 21:19
 */

namespace Netzexpert\Odoo2MagentoAttributes\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class IncrementId extends Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $invoiceNr = isset($item['odoo_invoice_nr']) ? $item['odoo_invoice_nr'] : $item['increment_id'];
                $item[$this->getData('name')] = $invoiceNr;
            }
        }

        return $dataSource;
    }
}
