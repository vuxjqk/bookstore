<?php

namespace App\Services;

class BookAttributesService
{
    public function getLanguages()
    {
        return [
            __('Vietnamese'),
            __('English'),
            __('French'),
            __('Japanese'),
            __('Chinese'),
        ];
    }

    public function getDimensions()
    {
        return ['13x20cm', '14x20cm', '16x24cm', '19x27cm', '21x29cm'];
    }

    public function getCoverTypes()
    {
        return [
            'hardcover' => __('Hardcover'),
            'paperback' => __('Paperback'),
        ];
    }

    public function getStatuses()
    {
        return [
            'available' => __('Available'),
            'out_of_stock' => __('Out of Stock'),
            'pre_order' => __('Pre-order'),
            'discontinued' => __('Discontinued'),
        ];
    }
}
