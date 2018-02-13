<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui;

use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeBridge;
use Spryker\Zed\CompanyGui\Dependency\QueryContainer\CompanyGuiToCompanyQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_COMPANY = 'QUERY_CONTAINER_COMPANY';
    public const FACADE_COMPANY = 'FACADE_COMPANY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyQueryContainer($container);
        $container = $this->addCompanyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_COMPANY] = function (Container $container) {
            return new CompanyGuiToCompanyQueryContainerBridge($container->getLocator()->company()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY] = function (Container $container) {
            return new CompanyGuiToCompanyFacadeBridge($container->getLocator()->company()->facade());
        };

        return $container;
    }
}
