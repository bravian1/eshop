<?php

namespace App\Services;

class VariantGeneratorService
{
    public static function generate(array $axes): array
    {
        if (empty($axes)) {
            return [];
        }

        // Extract values from each axis
        $valueArrays = [];
        foreach ($axes as $axis) {
            if (!empty($axis['values'])) {
                $valueArrays[] = $axis['values'];
            }
        }

        if (empty($valueArrays)) {
            return [];
        }

        // Generate Cartesian product
        $combinations = self::cartesianProduct($valueArrays);
        
        // Format for Filament repeater
        $variants = [];
        foreach ($combinations as $index => $combination) {
            $variants[] = [
                'sku' => '', // User will fill this
                'price_cents' => 0,
                'cost_cents' => null,
                'weight_g' => null,
                'width_mm' => null,
                'height_mm' => null,
                'depth_mm' => null,
                'is_active' => true,
                'combination_label' => implode(' / ', $combination),
                'axis_values' => $combination,
            ];
        }

        return $variants;
    }

    private static function cartesianProduct(array $arrays): array
    {
        $result = [[]];
        
        foreach ($arrays as $array) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($array as $item) {
                    $temp[] = array_merge($resultItem, [$item]);
                }
            }
            $result = $temp;
        }
        
        return $result;
    }
}