<?php

namespace Funarbe\DeliveryApi\Observer\Sales;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderExtension;

class OrderLoadAfter implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->getOrderExtensionDependency();
        }
        $attr = $order->getData('delivery_date');
        $extensionAttributes->setDeliveryDate($attr);
        $order->setExtensionAttributes($extensionAttributes);
    }

    private function getOrderExtensionDependency()
    {
        return ObjectManager::getInstance()->get(
            OrderExtension::class
        );
    }
}
