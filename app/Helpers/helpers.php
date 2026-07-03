<?php

if (!function_exists('getServiceIcon')) {
    function getServiceIcon($name) {
        $icons = [
            'Battery Jump Start' => 'fas fa-car-battery',
            'Brake Repair'       => 'fas fa-circle-notch',
            'Fuel Delivery'      => 'fas fa-gas-pump',
            'Tyre Change'        => 'fas fa-circle',
            'Engine Repair'      => 'fas fa-cogs',
            'Electrical'         => 'fas fa-bolt',
            'Diagnostics'        => 'fas fa-stethoscope',
        ];
        return $icons[$name] ?? 'fas fa-tools';
    }
}