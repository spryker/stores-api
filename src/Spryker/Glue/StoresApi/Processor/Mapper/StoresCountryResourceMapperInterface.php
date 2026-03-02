<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiStoreCountryAttributesTransfer;
use Generated\Shared\Transfer\CountryTransfer;

interface StoresCountryResourceMapperInterface
{
    public function mapCountryToStoresCountryRestAttributes(
        CountryTransfer $countryTransfer,
        ApiStoreCountryAttributesTransfer $apiStoreCountryAttributesTransfer
    ): ApiStoreCountryAttributesTransfer;
}
