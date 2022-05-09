<?php

namespace Rias\StatamicRedirect\Data\Concerns;

/**
 * Backport of `Statamic\Data\TracksQueriedRelations` to support Statamic 3.2.
 */
trait TracksQueriedRelations
{
    protected $selectedQueryRelations = [];

    public function selectedQueryRelations($relations)
    {
        $this->selectedQueryRelations = $relations;

        return $this;
    }
}
