<?php

use CpChart\Chart\Pie;
use CpChart\Data;
use CpChart\Image;

class ChartService
{
  public function __construct() {}

  public function generarGraficoTorta(
    string $graphTitle,
    string $dataValuesName,
    array $dataValues,
    string $dataLabelsName,
    array $dataLabels
  ): string {
    $enhancedLabels = [];
    foreach ($dataLabels as $index => $label) {
        $value = $dataValues[$index] ?? 0;
        $enhancedLabels[] = "{$label} ({$value})";
    }

    $chartData = new Data();
    $chartData->addPoints($dataValues, $dataValuesName);
    $chartData->addPoints($enhancedLabels, $dataLabelsName);
    $chartData->setAbscissa($dataLabelsName);

    $image = new Image(500, 250, $chartData);
    $image->setFontProperties(["FontName" => realpath(__DIR__ . "/../../public/fonts/OpenSans.ttf"), "FontSize" => 10]);
    $image->drawFilledRectangle(0, 0, 500, 250, ["R" => 255, "G" => 255, "B" => 255]);

    $pieChart = new Pie($image, $chartData);
    $pieChart->draw2DPie(250, 125, ["Radius" => 100, "DrawLabels" => true, "LabelStacked" => true]);

    $image->setFontProperties(["FontName" => realpath(__DIR__ . "/../../public/fonts/OpenSans.ttf"), "FontSize" => 12]);
    $image->drawText(250, 15, $graphTitle, ["Align" => TEXT_ALIGN_MIDDLEMIDDLE]);

    return $image->toDataURI();
  }
}
