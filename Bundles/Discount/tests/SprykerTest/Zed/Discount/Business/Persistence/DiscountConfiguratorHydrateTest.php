<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Persistence;

use ArrayObject;
use Codeception\Test\Unit;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrate;
use Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface;
use Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationMapper;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Persistence
 * @group DiscountConfiguratorHydrateTest
 * Add your own group annotations below this line
 */
class DiscountConfiguratorHydrateTest extends Unit
{
    /**
     * @uses DiscountQueryContainerInterface::queryDiscountWithStoresByFkDiscount()
     *
     * @return void
     */
    public function testHydrateDiscountShouldFillTransferWithDataFromEntities()
    {
        $discountEntity = $this->createDiscountEntity();

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')
            ->willReturn($discountQueryMock);
        $discountQueryMock->method('getFirst')
            ->willReturn($discountEntity);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscountWithStoresByFkDiscount')->willReturn($discountQueryMock);

        $discountConfiguratorHydrate = $this->createDiscountConfiguratorHydrate($discountQueryContainerMock);

        $hydratedDiscountConfiguration = $discountConfiguratorHydrate->getByIdDiscount(1);

        $this->assertEquals(
            $discountEntity->getDecisionRuleQueryString(),
            $hydratedDiscountConfiguration->getDiscountCondition()->getDecisionRuleQueryString()
        );

        $this->assertSame(
            $discountEntity->getAmount(),
            $hydratedDiscountConfiguration->getDiscountCalculator()->getAmount()
        );

        $this->assertEquals(
            $discountEntity->getCollectorQueryString(),
            $hydratedDiscountConfiguration->getDiscountCalculator()->getCollectorQueryString()
        );

        $this->assertEquals(
            $discountEntity->getCalculatorPlugin(),
            $hydratedDiscountConfiguration->getDiscountCalculator()->getCalculatorPlugin()
        );

        $this->assertEquals(
            $discountEntity->getDisplayName(),
            $hydratedDiscountConfiguration->getDiscountGeneral()->getDisplayName()
        );

        $this->assertEquals(
            $discountEntity->getDescription(),
            $hydratedDiscountConfiguration->getDiscountGeneral()->getDescription()
        );

        $this->assertEquals(
            $discountEntity->getValidFrom(),
            $hydratedDiscountConfiguration->getDiscountGeneral()->getValidFrom()
        );

        $this->assertEquals(
            $discountEntity->getValidTo(),
            $hydratedDiscountConfiguration->getDiscountGeneral()->getValidTo()
        );

        $this->assertEquals(
            $discountEntity->getIsActive(),
            $hydratedDiscountConfiguration->getDiscountGeneral()->getIsActive()
        );

        $this->assertEquals(
            $discountEntity->getIsExclusive(),
            $hydratedDiscountConfiguration->getDiscountGeneral()->getIsExclusive()
        );

        $this->assertEquals(
            $discountEntity->getFkDiscountVoucherPool(),
            $hydratedDiscountConfiguration->getDiscountVoucher()->getFkDiscountVoucherPool()
        );
    }

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface|null $discountQueryContainerMock
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface|null $discountEntityMapperMock
     *
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrate
     */
    protected function createDiscountConfiguratorHydrate(
        DiscountQueryContainerInterface $discountQueryContainerMock = null,
        DiscountEntityMapperInterface $discountEntityMapperMock = null
    ) {
        if (!$discountQueryContainerMock) {
            $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        }

        if (!$discountEntityMapperMock) {
            $discountEntityMapperMock = $this->createEntityMapperMock();
            $discountEntityMapperMock->method('getMoneyValueCollectionForEntity')
                ->willReturn(new ArrayObject());
        }

        $discountStoreRelationMapperMock = $this->createDiscountStoreRelationMapperMock();
        $discountConfigurationExpanderPlugins = [];

        return new DiscountConfiguratorHydrate(
            $discountQueryContainerMock,
            $discountEntityMapperMock,
            $discountStoreRelationMapperMock,
            $discountConfigurationExpanderPlugins
        );
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function createDiscountEntity()
    {
        $discountEntity = new SpyDiscount();
        $discountEntity->setAmount(10)
            ->setDecisionRuleQueryString('decisionRule string')
            ->setDisplayName('display name')
            ->setDescription('description')
            ->setCollectorQueryString('collector query string')
            ->setCalculatorPlugin('Calculator plugin')
            ->setValidFrom('2001-01-01')
            ->setValidTo('2001-01-01')
            ->setIsActive(true)
            ->setFkDiscountVoucherPool(1)
            ->setIsExclusive(true);

        return $discountEntity;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainerMock()
    {
        return $this->getMockBuilder(DiscountQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpyDiscountQuery::class)->setMethods(['find', 'getFirst'])->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface
     */
    protected function createEntityMapperMock()
    {
        return $this->getMockBuilder(DiscountEntityMapperInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationMapper
     */
    protected function createDiscountStoreRelationMapperMock()
    {
        return $this->getMockBuilder(DiscountStoreRelationMapper::class)->getMock();
    }
}
