<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestQueryParams;

final class GetNotificationHistoryQueryParams extends PageableQueryParams
{
    public function __construct(array $params = [])
    {
        if (isset($params['paginationToken'])) {
            $params['revision'] = $params['paginationToken'];
            unset($params['paginationToken']);
        }

        parent::__construct($params);
    }

    protected function collectProps(): array
    {
        $props = parent::collectProps();

        if (!empty($props['revision'])) {
            $props['paginationToken'] = $props['revision'];
            unset($props['revision']);
        }

        return $props;
    }
}
