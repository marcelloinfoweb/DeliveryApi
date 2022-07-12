<?php

namespace Funarbe\DeliveryApi\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderRepositoryPlugin
 */
class OrderRepositoryPlugin
{

    public const delivery_date = 'delivery_date';

    public const delivery_timeslot = 'delivery_timeslot';

    public const delivery_comment = 'delivery_comment';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected OrderExtensionFactory $extensionFactory;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Add "delivery_date" extension attribute to order data object to make it accessible in API data of order record
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $delivery_date = $order->getData(self::delivery_date);
        $delivery_timeslot = $order->getData(self::delivery_timeslot);
        $delivery_comment = $order->getData(self::delivery_comment);
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->extensionFactory->create();
        $extensionAttributes->setDeliveryDate($delivery_date);
        $extensionAttributes->setDeliveryTimeslot($delivery_timeslot);
        $extensionAttributes->setDeliveryTimeslot($delivery_comment);
        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    /**
     * Add "delivery_date" extension attribute to order data object to make it accessible in API data of all order list
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ): OrderSearchResultInterface {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $delivery_date = $order->getData(self::delivery_date);
            $delivery_timeslot = $order->getData(self::delivery_timeslot);
            $delivery_comment = $order->getData(self::delivery_comment);
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ?: $this->extensionFactory->create();
            $extensionAttributes->setDeliveryDate($delivery_date);
            $extensionAttributes->setDeliveryTimeslot($delivery_timeslot);
            $extensionAttributes->setDeliveryTimeslot($delivery_comment);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}
