<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi;

use Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder\CartCodeAdder;
use Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder\CartCodeAdderInterface;
use Spryker\Glue\CartCodesRestApi\Processor\Expander\DiscountByCartResourceRelationshipExpander;
use Spryker\Glue\CartCodesRestApi\Processor\Expander\DiscountByCartResourceRelationshipExpanderInterface;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapper;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapper;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface;
use Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilder;
use Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface getClient()
 */
class CartCodesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder\CartCodeAdderInterface
     */
    public function createCartCodeAdder(): CartCodeAdderInterface
    {
        return new CartCodeAdder(
            $this->getClient(),
            $this->createCartCodeRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface
     */
    public function createCartCodeRestResponseBuilder(): CartCodeRestResponseBuilderInterface
    {
        return new CartCodeRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCartCodeMapper(),
            $this->getCartsRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface
     */
    public function createCartCodeMapper(): CartCodeMapperInterface
    {
        return new CartCodeMapper();
    }

    /**
     * @return \Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface
     */
    public function createDiscountMapper(): DiscountMapperInterface
    {
        return new DiscountMapper();
    }

    /**
     * @return \Spryker\Glue\CartCodesRestApi\Processor\Expander\DiscountByCartResourceRelationshipExpanderInterface
     */
    public function createDiscountByCartResourceRelationshipExpander(): DiscountByCartResourceRelationshipExpanderInterface
    {
        return new DiscountByCartResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createDiscountMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface
     */
    public function getCartsRestApiResource(): CartsRestApiResourceInterface
    {
        return $this->getProvidedDependency(CartCodesRestApiDependencyProvider::CARTS_RESOURCE);
    }
}
