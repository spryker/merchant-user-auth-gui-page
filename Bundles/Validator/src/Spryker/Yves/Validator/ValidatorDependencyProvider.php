<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Validator\Plugin\Validator\ConstraintFactoryValidatorPlugin;
use Spryker\Yves\Validator\Plugin\Validator\MetadataFactoryValidatorPlugin;

class ValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_VALIDATOR = 'PLUGINS_VALIDATOR';
    public const PLUGINS_CORE_VALIDATOR = 'PLUGINS_CORE_VALIDATOR';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_VALIDATOR, function () {
            return $this->getValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    protected function getValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCoreValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CORE_VALIDATOR, function () {
            return $this->getCoreValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    protected function getCoreValidatorPlugins(): array
    {
        return [
            new MetadataFactoryValidatorPlugin(),
            new ConstraintFactoryValidatorPlugin(),
        ];
    }
}
