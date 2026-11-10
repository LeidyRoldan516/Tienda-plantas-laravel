<?php

namespace App\Interfaces;

use App\Exceptions\CatalogoNftException;

interface CatalogoNftInterface
{
    /**
     * @return array{data: list<array>, meta: array{current_page: int, per_page: int, total: int, last_page: int}}
     *
     * @throws CatalogoNftException
     */
    public function consultar(int $pagina): array;
}
