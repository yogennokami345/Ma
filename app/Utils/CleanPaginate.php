<?php

namespace App\Utils;

class CleanPaginate
{
    public static function clean($data)
    {
        $paginate = $data->toArray();

        unset($paginate['prev_page_url']);
        unset($paginate['path']);
        unset($paginate['next_page_url']);
        unset($paginate['links']);
        unset($paginate['last_page_url']);
        unset($paginate['first_page_url']);
        unset($paginate['from']);
        unset($paginate['to']);

        return collect($paginate);
    }
}
