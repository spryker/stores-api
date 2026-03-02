<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi;

use Spryker\Glue\Kernel\AbstractStorefrontApiFactory;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCountryClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCurrencyClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToGlossaryStorageClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToLocaleClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface;
use Spryker\Glue\StoresApi\Processor\Builder\StoresApiResponseBuilder;
use Spryker\Glue\StoresApi\Processor\Builder\StoresApiResponseBuilderInterface;
use Spryker\Glue\StoresApi\Processor\Expander\StoreExpander;
use Spryker\Glue\StoresApi\Processor\Expander\StoreExpanderInterface;
use Spryker\Glue\StoresApi\Processor\Mapper\StoresCountryResourceMapper;
use Spryker\Glue\StoresApi\Processor\Mapper\StoresCountryResourceMapperInterface;
use Spryker\Glue\StoresApi\Processor\Mapper\StoresCurrencyResourceMapper;
use Spryker\Glue\StoresApi\Processor\Mapper\StoresCurrencyResourceMapperInterface;
use Spryker\Glue\StoresApi\Processor\Reader\StoreReader;
use Spryker\Glue\StoresApi\Processor\Reader\StoreReaderInterface;
use Spryker\Glue\StoresApi\Processor\Reader\StoresCountryReader;
use Spryker\Glue\StoresApi\Processor\Reader\StoresCountryReaderInterface;
use Spryker\Glue\StoresApi\Processor\Reader\StoresCurrencyReader;
use Spryker\Glue\StoresApi\Processor\Reader\StoresCurrencyReaderInterface;
use Spryker\Glue\StoresApi\Processor\Resolver\StoreResolver;
use Spryker\Glue\StoresApi\Processor\Resolver\StoreResolverInterface;
use Spryker\Glue\StoresApi\Processor\StoreProvider\StoreProvider;
use Spryker\Glue\StoresApi\Processor\StoreProvider\StoreProviderInterface;
use Spryker\Glue\StoresApi\Processor\Validator\StoreValidator;
use Spryker\Glue\StoresApi\Processor\Validator\StoreValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\StoresApi\StoresApiConfig getConfig()
 */
class StoresApiFactory extends AbstractStorefrontApiFactory
{
    public function createStoreReader(): StoreReaderInterface
    {
        return new StoreReader(
            $this->getStoreStorageClient(),
            $this->createStoresApiResponseBuilder(),
            $this->createStoreExpander(),
            $this->getLocaleClient(),
            $this->getStoreClient(),
        );
    }

    public function createStoresApiResponseBuilder(): StoresApiResponseBuilderInterface
    {
        return new StoresApiResponseBuilder($this->getGlossaryStorageClient());
    }

    public function getStoreStorageClient(): StoresApiToStoreStorageClientInterface
    {
        return $this->getProvidedDependency(StoresApiDependencyProvider::CLIENT_STORE_STORAGE);
    }

    public function createRequest(): Request
    {
        return Request::createFromGlobals();
    }

    public function createStoresCountryReader(): StoresCountryReaderInterface
    {
        return new StoresCountryReader(
            $this->getCountryClient(),
            $this->createStoresCountryResourceMapper(),
        );
    }

    public function createStoresCountryResourceMapper(): StoresCountryResourceMapperInterface
    {
        return new StoresCountryResourceMapper();
    }

    public function getCountryClient(): StoresApiToCountryClientInterface
    {
        return $this->getProvidedDependency(StoresApiDependencyProvider::CLIENT_COUNTRY);
    }

    public function createStoresCurrencyReader(): StoresCurrencyReaderInterface
    {
        return new StoresCurrencyReader(
            $this->getCurrencyClient(),
            $this->createStoresCurrencyResourceMapper(),
        );
    }

    public function createStoresCurrencyResourceMapper(): StoresCurrencyResourceMapperInterface
    {
        return new StoresCurrencyResourceMapper();
    }

    public function createStoreExpander(): StoreExpanderInterface
    {
        return new StoreExpander(
            $this->createStoresCountryReader(),
            $this->createStoresCurrencyReader(),
        );
    }

    public function getCurrencyClient(): StoresApiToCurrencyClientInterface
    {
        return $this->getProvidedDependency(StoresApiDependencyProvider::CLIENT_CURRENCY);
    }

    public function getGlossaryStorageClient(): StoresApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(StoresApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    public function getLocaleClient(): StoresApiToLocaleClientInterface
    {
        return $this->getProvidedDependency(StoresApiDependencyProvider::CLIENT_LOCALE);
    }

    public function getStoreClient(): StoresApiToStoreClientInterface
    {
        return $this->getProvidedDependency(StoresApiDependencyProvider::CLIENT_STORE);
    }

    public function createStoreRequestValidator(): StoreValidatorInterface
    {
        return new StoreValidator(
            $this->getStoreClient(),
            $this->getStoreStorageClient(),
        );
    }

    public function createStoreResolver(): StoreResolverInterface
    {
        return new StoreResolver(
            $this->getStoreStorageClient(),
            $this->createRequest(),
        );
    }

    public function createStoreProvider(): StoreProviderInterface
    {
        return new StoreProvider($this->createStoreResolver());
    }
}
