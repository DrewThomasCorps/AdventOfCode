<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-08
 * Time: 17:09
 */

class Layer
{
    private int $height;
    private int $width;
    public array $rows;

    public function __construct(string $pixels, int $height, int $width)
    {
        $this->height = $height;
        $this->width = $width;
        $this->addPixels($pixels);
    }

    private function addPixels(string $pixels)
    {
        $rowsPixels = str_split($pixels, $this->width);
        foreach ($rowsPixels as $row) {
            $pixels = str_split($row);
            $this->rows[] = $pixels;
        }
    }

    public function getPixelDigitCount(int $digit)
    {
        return array_reduce($this->rows, function ($carry, $row) use ($digit) {
            $carry += array_reduce($row, function ($carry, $pixel) use ($digit) {
                if ((int)$pixel === $digit) {
                    $carry++;
                }
                return $carry;
            });
            return $carry;
        });
    }

    public function getPixelAtPosition($row, $column): string
    {
        return $this->rows[$row][$column];
    }
}