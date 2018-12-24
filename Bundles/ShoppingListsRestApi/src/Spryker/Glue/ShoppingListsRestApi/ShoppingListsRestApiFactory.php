<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapper;
use Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdder;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdderInterface;

/**
 * @method \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface getClient()
 */
class ShoppingListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdderInterface
     */
    public function createShoppingListItemAdder(): ShoppingListItemAdderInterface
    {
        return new ShoppingListItemAdder(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createShoppingListItemResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface
     */
    public function createShoppingListItemResourceMapper(): ShoppingListItemsResourceMapperInterface
    {
        return new ShoppingListItemsResourceMapper();
    }
}
