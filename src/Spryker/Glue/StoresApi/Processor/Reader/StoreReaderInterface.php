<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface StoreReaderInterface
{
    public function getStore(string $storeId, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    public function getStoreCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;
}
