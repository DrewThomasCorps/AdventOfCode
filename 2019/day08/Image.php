<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-08
 * Time: 17:08
 */

require_once __DIR__ . "/Layer.php";

class Image
{
    private int $height;
    private int $width;
    private array $layers = [];

    public function __construct(int $height, int $width, string $layers)
    {
        $this->height = $height;
        $this->width = $width;
        $this->setLayers($layers);
    }

    private function setLayers(string $input)
    {
        $layerPixels = str_split($input, $this->height * $this->width);
        foreach ($layerPixels as $layer) {
            $this->layers[] = new Layer($layer, $this->height, $this->width);
        }
    }

    public function getLayerWithFewestOfDigit(int $digit): Layer
    {
        $lowestCount = $this->height * $this->width + 1;
        $returnLayer = null;
        foreach ($this->layers as $layer) {
            $count = $layer->getPixelDigitCount($digit);
            if ($count < $lowestCount) {
                $lowestCount = $count;
                $returnLayer = $layer;
            }
        }
        return $returnLayer;
    }

    public function print()
    {
        for ($heightIndex = 0; $heightIndex < $this->height; $heightIndex++) {
            for ($widthIndex = 0; $widthIndex < $this->width; $widthIndex++) {
                echo ($this->getFirstVisiblePixelAtPosition($heightIndex, $widthIndex) === "1" ? "*" : " ") . " ";
            }
            echo "\n";
        }
    }

    private function getFirstVisiblePixelAtPosition(int $row, int $column): string
    {
        foreach ($this->layers as $layer) {
            $layerPixel = $layer->getPixelAtPosition($row, $column);
            if ($layerPixel !== "2") {
                return $layerPixel;
            }
        }
        return " ";
    }
}