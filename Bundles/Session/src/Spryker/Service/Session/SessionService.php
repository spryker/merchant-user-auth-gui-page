<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Session;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Session\SessionServiceFactory getFactory()
 */
class SessionService extends AbstractService implements SessionServiceInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function checkZedSessionHealthIndicator(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createZedHealthCheckIndicator()->executeHealthCheck();
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function checkYvesSessionHealthIndicator(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createYvesHealthCheckIndicator()->executeHealthCheck();
    }
}
