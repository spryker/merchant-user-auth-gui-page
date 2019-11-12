<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Filter\Service;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;

class ServiceFilter implements ServiceFilterInterface
{
    /**
     * @var \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected $healthCheckPlugins;

    /**
     * @param \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     */
    public function __construct(array $healthCheckPlugins)
    {
        $this->healthCheckPlugins = $healthCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(HealthCheckRequestTransfer $healthCheckRequestTransfer): array
    {
        $requestedServices = $healthCheckRequestTransfer->getServices();

        if (strlen($requestedServices) === 0) {
            return $this->healthCheckPlugins;
        }

        return $this->filterServicesByName($requestedServices);
    }

    /**
     * @param string $requestedServices
     *
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterServicesByName(string $requestedServices): array
    {
        $requestedServicesArray = explode(',', $requestedServices);
        $filteredServicePlugins = [];

        foreach ($this->healthCheckPlugins as $healthCheckPlugin) {
            if (in_array($healthCheckPlugin->getName(), $requestedServicesArray)) {
                $filteredServicePlugins[] = $healthCheckPlugin;
            }
        }

        return $filteredServicePlugins;
    }
}
