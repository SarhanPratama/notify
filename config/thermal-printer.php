<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Thermal Printer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for thermal printer invoice printing using ESC/POS commands.
    | Supports network, USB, and Windows printer connections.
    |
    */

    'default' => env('THERMAL_PRINTER_DEFAULT', 'network'),

    'connections' => [
        'network' => [
            'host' => env('THERMAL_PRINTER_HOST', '192.168.1.100'),
            'port' => env('THERMAL_PRINTER_PORT', 9100),
        ],

        'windows' => [
            'printer_name' => env('THERMAL_PRINTER_NAME', 'ThermalPrinter'),
        ],

        'usb' => [
            'vendor_id' => env('THERMAL_PRINTER_VENDOR_ID'),
            'product_id' => env('THERMAL_PRINTER_PRODUCT_ID'),
        ],
    ],

    'invoice' => [
        'store_name' => env('STORE_NAME', 'SEROo OUTLET'),
        'store_address' => env('STORE_ADDRESS', ''),
        'store_phone' => env('STORE_PHONE', ''),
        'footer_message' => env('INVOICE_FOOTER', 'Terima Kasih\nSeroo Outlet System'),
        'paper_width' => 32, // characters per line for 58mm paper
    ],
];