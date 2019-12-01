<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-01
 * Time: 00:22
 */

class FileReader
{
    private /** @noinspection PhpMissingFieldTypeInspection */
        $handle;
    private bool $hasColumns = false;
    private string $delimiter = ",";

    public function __construct(string $file, bool $hasColumns = false, string $delimiter = ",")
    {
        $this->handle = fopen(__DIR__ . "/../files/$file", 'r');
        $this->hasColumns = $hasColumns;
        $this->delimiter = $delimiter;
    }

    public function getData(): array
    {
        $data = [];
        while (($row = fgetcsv($this->handle, 0, $this->delimiter)) !== false) {
            if ($this->hasColumns) {
                $data[] = $row;
            } else {
                $data[] = $row[0];
            }
        }
        return $data;
    }
}