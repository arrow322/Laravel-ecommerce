<?php

namespace Webkul\Inventory\Repositories;

use Webkul\Core\Eloquent\Repository;

class InventorySourceRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Inventory\Contracts\InventorySource::class;
    }

    /**
     * Returns channel inventory source ids.
     *
     * @return collection
     */
    public function getChannelInventorySourceIds()
    {
        static $channelInventorySourceIds;

        if ($channelInventorySourceIds) {
            return $channelInventorySourceIds;
        }

        $found = core()->getCurrentChannel()->inventory_sources()
            ->where('status', 1)
            ->pluck('id');

        return $channelInventorySourceIds = ($found ? $found : collect([]));
    }
}
