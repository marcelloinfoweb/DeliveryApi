<?php

namespace Funarbe\DeliveryApi\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product as ProductModel;

class ProductCustAttr
{
    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductInterface $entity
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function afterGet(
        ProductRepositoryInterface $subject,
        ProductInterface $entity
    ): ProductInterface {
        $product = $entity;
        /** Get Current Extension Attributes from Product */
        $extensionAttributes = $product->getExtensionAttributes();
        $extensionAttributes->setDeliveryDate(); // custom field value set
        $product->setExtensionAttributes($extensionAttributes);
        return $product;
    }

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductSearchResultsInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function afterGetList(
        ProductRepositoryInterface $subject,
        ProductSearchResultsInterface $searchCriteria
    ): ProductSearchResultsInterface {
        $products = [];
        foreach ($searchCriteria->getItems() as $entity) {
            /** Get Current Extension Attributes from Product */
            $extensionAttributes = $entity->getExtensionAttributes();
            $extensionAttributes->setDeliveryDate(); // custom field value set
            $entity->setExtensionAttributes($extensionAttributes);
            $products[] = $entity;
        }
        $searchCriteria->setItems($products);
        return $searchCriteria;
    }
}
