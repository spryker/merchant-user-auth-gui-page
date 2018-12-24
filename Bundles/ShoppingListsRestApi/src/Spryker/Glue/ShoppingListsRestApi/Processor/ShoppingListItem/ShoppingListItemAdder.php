<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    protected const SELF_LINK_FORMAT_PATTERN = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface
     */
    protected $shoppingListItemResourceMapper;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface $shoppingListItemResourceMapper
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListItemsResourceMapperInterface $shoppingListItemResourceMapper
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shoppingListItemResourceMapper = $shoppingListItemResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItem(
        RestRequestInterface $restRequest,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestResponseInterface {
        $shoppingListUuid = $this->findShoppingListIdentifier($restRequest);
        if (!$shoppingListUuid) {
            return $this->createShoppingListBadRequestErrorResponse();
        }

        $restShoppingListItemRequestTransfer = $this->shoppingListItemResourceMapper->mapRestRequestToRestShoppingListItemRequestTransfer(
            $restRequest,
            new RestShoppingListItemRequestTransfer(),
            $restShoppingListItemAttributesTransfer
        );

        $restShoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->addItem($restShoppingListItemRequestTransfer);

        if (!$restShoppingListItemResponseTransfer->getIsSuccess()) {
            return $this->createAddItemFailedErrorResponse($restShoppingListItemResponseTransfer->getErrors());
        }

        $shoppingListItemTransfer = $restShoppingListItemResponseTransfer->getShoppingListItem();

        $restShoppingListItemAttributesTransfer = $this->shoppingListItemResourceMapper->mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
            $shoppingListItemTransfer,
            new RestShoppingListItemAttributesTransfer()
        );

        return $this->buildResponse(
            $restShoppingListItemAttributesTransfer,
            $shoppingListItemTransfer,
            $shoppingListUuid
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findShoppingListIdentifier(RestRequestInterface $restRequest): ?string
    {
        $shoppingListResource = $restRequest->findParentResourceByType(ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS);
        if ($shoppingListResource !== null) {
            return $shoppingListResource->getId();
        }

        return null;
    }

    /**
     * @param string $shoppingListResourceId
     * @param string $shoppingListItemResourceId
     *
     * @return string
     */
    protected function createSelfLinkForShoppingListItem(
        string $shoppingListResourceId,
        string $shoppingListItemResourceId
    ): string {
        return sprintf(
            static::SELF_LINK_FORMAT_PATTERN,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $shoppingListResourceId,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $shoppingListItemResourceId
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createShoppingListBadRequestErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_UUID_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_UUID_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param \ArrayObject $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createAddItemFailedErrorResponse(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $restErrorMessageTransfer) {
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param string $shoppingListUuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildResponse(
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer,
        string $shoppingListUuid
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $shoppingListItemResource = $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $shoppingListItemTransfer->getUuid(),
            $restShoppingListItemAttributesTransfer
        );

        $shoppingListItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForShoppingListItem(
                $shoppingListUuid,
                $shoppingListItemTransfer->getUuid()
            )
        );

        return $restResponse->addResource($shoppingListItemResource);
    }
}
