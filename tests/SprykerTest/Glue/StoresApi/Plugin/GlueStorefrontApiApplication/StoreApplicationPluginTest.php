<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\StoresApi\Plugin\GlueStorefrontApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\StoresApi\StoresApiFactory;
use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group StoresApi
 * @group Plugin
 * @group GlueStorefrontApiApplication
 * @group StoreApplicationPluginTest
 * Add your own group annotations below this line
 */
class StoreApplicationPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const DE_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const AT_STORE_NAME = 'AT';

    /**
     * @var string
     */
    protected const HEADER_STORE_NAME = 'Store';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAME = '_store';

    /**
     * @var string
     */
    protected const TEST_URL = '/';

    /**
     * @var \SprykerTest\Glue\StoresApi\StoresApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testStoreApplicationPluginSetStoreFromHeader(): void
    {
        // Arrange
        $mockRequest = Request::create(static::TEST_URL, Request::METHOD_GET);
        $mockRequest->headers->set(static::HEADER_STORE_NAME, static::DE_STORE_NAME);

        // Act
        $resultContainer = $this->provide($mockRequest);

        // Assert
        $this->assertTrue($resultContainer->has(static::SERVICE_STORE));
        $this->assertEquals(static::DE_STORE_NAME, $resultContainer->get(static::SERVICE_STORE));
    }

    /**
     * @return void
     */
    public function testStoreApplicationPluginSetStoreFromParameter(): void
    {
        // Arrange
        $mockRequest = Request::create(static::TEST_URL, Request::METHOD_GET, [static::PARAMETER_STORE_NAME => static::DE_STORE_NAME]);

        // Act
        $resultContainer = $this->provide($mockRequest);

        // Assert
        $this->assertTrue($resultContainer->has(static::SERVICE_STORE));
        $this->assertEquals(static::DE_STORE_NAME, $resultContainer->get(static::SERVICE_STORE));
    }

    /**
     * @return void
     */
    public function testStoreApplicationPluginSetStoreFromHeaderAndParameter(): void
    {
        // Arrange
        $mockRequest = Request::create(static::TEST_URL, Request::METHOD_GET, [static::PARAMETER_STORE_NAME => static::DE_STORE_NAME]);
        $mockRequest->headers->set(static::HEADER_STORE_NAME, static::AT_STORE_NAME);

        // Act
        $resultContainer = $this->provide($mockRequest);

        // Assert
        $this->assertTrue($resultContainer->has(static::SERVICE_STORE));
        $this->assertEquals(static::DE_STORE_NAME, $resultContainer->get(static::SERVICE_STORE));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $mockRequest
     *
     * @return \Spryker\Glue\StoresApi\StoresApiFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockStoresApiFactory(Request $mockRequest): StoresApiFactory
    {
        $mockFactory = $this->createMock(StoresApiFactory::class);
        $mockFactory->method('createRequest')
            ->willReturn($mockRequest);

        return $mockFactory;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $mockRequest
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function provide(Request $mockRequest): ContainerInterface
    {
        $container = $this->tester->createContainer();
        $mockFactory = $this->getMockStoresApiFactory($mockRequest);
        $storeApplicationPlugin = $this->tester->createStoreApplicationPlugin();
        $storeApplicationPlugin->setFactory($mockFactory);

        return $storeApplicationPlugin->provide($container);
    }
}
