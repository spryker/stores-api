<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\StoresApi\Api\Storefront\Provider;

use Generated\Api\Storefront\StoresStorefrontResource;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Client\StoreStorage\StoreStorageClientInterface;
use Spryker\Glue\Store\StoreConfig;
use Spryker\Service\Serializer\SerializerServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Currencies;

class StoresStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string ERROR_MESSAGE_STORE_NOT_FOUND = 'Store not found';

    public function __construct(
        protected StoreStorageClientInterface $storeStorageClient,
        protected StoreClientInterface $storeClient,
        protected SerializerServiceInterface $serializer,
    ) {
    }

    protected function provideItem(): object|null
    {
        $name = $this->getUriVariables()['name'] ?? null;

        if ($name === null) {
            return null;
        }

        $storeStorageTransfer = $this->storeStorageClient->findStoreByName($name);

        if ($storeStorageTransfer === null) {
            // detail carries the period-terminated message for backward-compat with the old Glue REST API
            // (which checked errors[0].detail). message is without a trailing period for the SAPI convention
            // (which checks errors[0].message). Both fields must satisfy their respective test suites.
            throw (new GlueApiException(
                Response::HTTP_NOT_FOUND,
                StoreConfig::RESPONSE_CODE_STORE_NOT_FOUND,
                static::ERROR_MESSAGE_STORE_NOT_FOUND,
            ))->setErrors([[
                'code' => StoreConfig::RESPONSE_CODE_STORE_NOT_FOUND,
                'status' => Response::HTTP_NOT_FOUND,
                'detail' => StoreConfig::RESPONSE_MESSAGE_STORE_NOT_FOUND,
                'message' => static::ERROR_MESSAGE_STORE_NOT_FOUND,
            ]]);
        }

        return $this->serializer->denormalize(
            $this->prepareResourceData($storeStorageTransfer),
            StoresStorefrontResource::class,
        );
    }

    /**
     * @return array<\Generated\Api\Storefront\StoresStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $resources = [];
        $storeNames = $this->storeStorageClient->getStoreNames();

        foreach ($storeNames as $storeName) {
            $storeStorageTransfer = $this->storeStorageClient->findStoreByName($storeName);

            if ($storeStorageTransfer === null) {
                continue;
            }

            $resources[] = $this->serializer->denormalize(
                $this->prepareResourceData($storeStorageTransfer),
                StoresStorefrontResource::class,
            );
        }

        return $resources;
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareResourceData(StoreStorageTransfer $storeStorageTransfer): array
    {
        $locales = [];

        foreach ($storeStorageTransfer->getAvailableLocaleIsoCodes() as $code => $name) {
            $locales[] = [
                'code' => $code,
                'name' => is_string($name) ? $name : $code,
            ];
        }

        $countryNames = $storeStorageTransfer->getCountryNames();

        return [
            'name' => $storeStorageTransfer->getName(),
            'defaultCurrency' => $storeStorageTransfer->getDefaultCurrencyIsoCode(),
            'timeZone' => $this->storeClient->getStoreByName($storeStorageTransfer->getNameOrFail())->getTimezone(),
            'currencies' => array_map(
                fn (string $code): array => [
                    'code' => $code,
                    'name' => Currencies::getName($code),
                ],
                $storeStorageTransfer->getAvailableCurrencyIsoCodes(),
            ),
            'locales' => $locales,
            'countries' => array_map(
                static fn (string $iso2Code): array => [
                    'iso2Code' => $iso2Code,
                    'iso3Code' => '',
                    'name' => $countryNames[$iso2Code] ?? $iso2Code,
                    'postalCodeMandatory' => false,
                    'postalCodeRegex' => null,
                ],
                $storeStorageTransfer->getCountries(),
            ),
        ];
    }
}
