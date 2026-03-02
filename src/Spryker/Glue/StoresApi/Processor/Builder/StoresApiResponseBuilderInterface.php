<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Builder;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoresApiResponseBuilderInterface
{
    public function createSingleResourceGlueResponseTransfer(
        string $currentLocale,
        GlueResponseTransfer $glueResponseTransfer,
        ?StoreStorageTransfer $storeStorageTransfer
    ): GlueResponseTransfer;

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapStoreStorageTransfersToCollectionResourceGlueResponseTransfer(
        array $storeStorageTransfers,
        GlueResponseTransfer $glueResponseTransfer
    ): GlueResponseTransfer;

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     *
     * @return array<mixed>
     */
    public function mapStoreStorageTransfersToStoresArray(array $storeStorageTransfers): array;

    public function create404GlueResponseTransfer(string $currentLocale): GlueResponseTransfer;

    public function mapStoreTransferToStoreStorageTransfer(
        StoreTransfer $storeTransfer,
        StoreStorageTransfer $storeStorageTransfer
    ): StoreStorageTransfer;
}
