# Thermal Printer Configuration for Invoice Printing

This document explains how to configure thermal printer invoice printing for your Laravel application using Blueprint brand thermal printers.

## Requirements

- **PHP Extension**: Ensure the `escpos` PHP extension is available (usually included with mike42/escpos-php)
- **Printer**: Blueprint brand thermal printer (ESC/POS compatible)
- **Connection**: Network, USB, or Windows printer connection

## Configuration

### 1. Environment Variables

Add these variables to your `.env` file:

```env
# Thermal Printer Configuration
THERMAL_PRINTER_DEFAULT=network
THERMAL_PRINTER_HOST=192.168.1.100
THERMAL_PRINTER_PORT=9100
THERMAL_PRINTER_NAME=ThermalPrinter

# Store Information
STORE_NAME="SEROo OUTLET"
STORE_ADDRESS="Your Store Address"
STORE_PHONE="Your Phone Number"
INVOICE_FOOTER="Terima Kasih\nSeroo Outlet System"
```

### 2. Connection Types

#### Network Connection (Recommended)
```env
THERMAL_PRINTER_DEFAULT=network
THERMAL_PRINTER_HOST=192.168.1.100  # Printer IP address
THERMAL_PRINTER_PORT=9100           # Default ESC/POS port
```

#### Windows Printer
```env
THERMAL_PRINTER_DEFAULT=windows
THERMAL_PRINTER_NAME=ThermalPrinter  # Printer name in Windows
```

#### USB Connection
```env
THERMAL_PRINTER_DEFAULT=usb
THERMAL_PRINTER_VENDOR_ID=04b8    # Printer vendor ID
THERMAL_PRINTER_PRODUCT_ID=0202   # Printer product ID
```

## Testing the Printer

### 1. Test Configuration
Run this command to test your configuration:

```bash
php artisan tinker
```

Then run:
```php
use App\Http\Controllers\PiutangController;
$controller = new PiutangController();
$connector = $controller->getPrinterConnector();
echo 'Connector type: ' . get_class($connector) . PHP_EOL;
```

### 2. Test Print
To test actual printing, visit any piutang detail page and click "Cetak Thermal".

## Troubleshooting

### Common Issues

1. **Connection Failed**
   - Check printer IP address and port
   - Ensure printer is powered on and connected to network
   - Verify firewall settings allow connection to printer port

2. **Printer Not Found (Windows)**
   - Install printer drivers
   - Check printer name in Windows Control Panel
   - Ensure printer is set as default if needed

3. **USB Connection Issues**
   - Find correct vendor/product IDs using `lsusb` (Linux) or Device Manager (Windows)
   - Ensure proper permissions for USB device access

4. **Print Quality Issues**
   - Check paper type and size (usually 58mm or 80mm)
   - Adjust paper width in config if needed
   - Verify printer settings match your paper size

### Debug Mode

For testing without a physical printer, set the default connection to `file`:

```env
THERMAL_PRINTER_DEFAULT=file
```

This will output to console instead of printing.

## Printer Specifications

### Supported Blueprint Models
- Most ESC/POS compatible thermal printers
- 58mm and 80mm paper widths supported
- Network, USB, and serial connections

### Paper Size Configuration
Adjust paper width in `config/thermal-printer.php`:

```php
'paper_width' => 32, // 32 characters for 58mm paper
// or
'paper_width' => 48, // 48 characters for 80mm paper
```

## Usage

1. Navigate to any piutang detail page (`/admin/piutang/{nobukti}`)
2. Click the "Cetak Thermal" button
3. Invoice will be printed on the configured thermal printer

## Invoice Format

The thermal invoice includes:
- Store header
- Outlet information
- Invoice number and dates
- Product list with quantities and prices
- Payment totals and status
- Payment history (if any)
- Footer with timestamp

Paper cutting is automatic after each print.
