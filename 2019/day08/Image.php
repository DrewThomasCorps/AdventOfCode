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

}