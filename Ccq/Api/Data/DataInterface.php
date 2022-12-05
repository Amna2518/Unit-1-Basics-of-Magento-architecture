<?php
declare(strict_types=1);

namespace RLTSquare\Ccq\Api\Data;

interface DataInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data): void;

    /**
     * @return array
     */
    public function getData(): array;
}
