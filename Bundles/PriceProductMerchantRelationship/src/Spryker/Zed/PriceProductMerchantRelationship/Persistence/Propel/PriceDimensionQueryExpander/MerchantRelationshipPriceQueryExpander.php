<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class MerchantRelationshipPriceQueryExpander implements MerchantRelationshipPriceQueryExpanderInterface
{
    public const COL_ID_PRICE_PRODUCT_STORE = 'spy_price_product_store.id_price_product_store';

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildBusinessUnitPriceDimensionCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        $idBusinessUnit = $this->findIdMerchantRelationship($priceProductCriteriaTransfer);

        return (new QueryCriteriaTransfer())
            ->addJoin(
                $this->createJoin($idBusinessUnit)
            )
            ->setWithColumns([
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP => PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    protected function findIdMerchantRelationship(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?int
    {
        if ($priceProductCriteriaTransfer->getPriceDimension()
            && $priceProductCriteriaTransfer->getPriceDimension()->getIdMerchantRelationship()) {
            return $priceProductCriteriaTransfer->getPriceDimension()->getIdBusinessUnit();
        }

        if (!$priceProductCriteriaTransfer->getQuote()) {
            return null;
        }

        $customerTransfer = $priceProductCriteriaTransfer->getQuote()->getCustomer();
        if (!$customerTransfer) {
            return null;
        }

        if (!$customerTransfer->getCompanyUserTransfer()) {
            return null;
        }

        //TODO need to get merchantIds by CompanyBusinessUnit
        return $customerTransfer->getCompanyUserTransfer()->getFkCompanyBusinessUnit();
    }

    /**
     * @param int|null $idBusinessUnit
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createJoin(?int $idBusinessUnit): QueryJoinTransfer
    {
        $left[] = static::COL_ID_PRICE_PRODUCT_STORE;
        $right[] = SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRICE_PRODUCT_STORE;

        if ($idBusinessUnit) {
            $left[] = SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP;
            $right[] = $idBusinessUnit;
        }

        return (new QueryJoinTransfer())
            ->setLeft($left)
            ->setRight($right)
            ->setJoinType(Criteria::LEFT_JOIN);
    }
}
