<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueBusinessFactory getFactory()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface getRepository()()
 */
class MinimumOrderValueFacade extends AbstractFacade implements MinimumOrderValueFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function installMinimumOrderValueTypes(): void
    {
        $this->getFactory()
            ->createMinimumOrderValueTypeInstaller()
            ->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueThresholdInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function saveMinimumOrderValue(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        return $this->getFactory()
            ->createMinimumOrderValueWriter()
            ->saveMinimumOrderValue($minimumOrderValueTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function getMinimumOrderValueTypeByKey(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        return $this->getFactory()
            ->createMinimumOrderValueTypeReader()
            ->getMinimumOrderValueTypeByKey($minimumOrderValueTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function getMinimumOrderValues(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        return $this->getFactory()
            ->createMinimumOrderValueReader()
            ->getMinimumOrderValues($storeTransfer, $currencyTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCheckoutMinimumOrderValue(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        return $this->getFactory()
            ->createHardThresholdChecker()
            ->checkQuoteForHardThreshold($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function saveSalesOrderMinimumOrderValueExpense(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        return $this->getFactory()
            ->createExpenseSaver()
            ->saveSalesOrderMinimumOrderValueExpense($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return bool
     */
    public function isThresholdValid(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
    ): bool {
        return $this->getFactory()
            ->createMinimumOrderValueStrategyResolver()
            ->resolveMinimumOrderValueStrategy($minimumOrderValueThresholdTransfer->getMinimumOrderValueType()->getKey())
            ->isValid($minimumOrderValueThresholdTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addMinimumOrderValueMessages(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createThresholdMessenger()
            ->addMinimumOrderValueMessages($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function removeMinimumOrderValueExpenses(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createExpenseRemover()
            ->removeMinimumOrderValueExpenses($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function addMinimumOrderValueExpenses(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createExpenseCalculator()
            ->addMinimumOrderValueExpenses($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int|null
     */
    public function findMinimumOrderValueTaxSetId(): ?int
    {
        return $this->getRepository()->findMinimumOrderValueTaxSetId();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveMinimumOrderValueTaxSet(int $idTaxSet): void
    {
        $this->getEntityManager()->saveMinimumOrderValueTaxSet($idTaxSet);
    }
}
