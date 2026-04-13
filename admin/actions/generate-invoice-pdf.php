<?php
/**
 * Power Watch - PDF Invoice Generator
 * Server-side PDF generation with proper formatting
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/connection.php';
require_once '../../includes/fpdf/fpdf.php'; 

// Ensure database connection is open before proceeding
if (empty(Database::$connection)) {
    Database::setUpConnection();
}

class InvoicePDF extends FPDF {
    private $orderData;
    
    // Brand Colors
    private $darkBlue = array(10, 17, 31);
    private $gold = array(212, 175, 55);
    
    function __construct($orderData) {
        parent::__construct();
        $this->orderData = $orderData;
    }
    
    // Page header
    function Header() {
        // Power Watch Branding
        $this->SetFont('Arial', 'B', 28);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->Cell(110);
        $this->Cell(80, 10, 'POWER WATCH', 0, 1, 'R');
        
        // Company details
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(110);
        $this->Cell(80, 5, 'No. 123, Main Street, Panadura', 0, 1, 'R');
        $this->Cell(110);
        $this->Cell(80, 5, 'Phone: +94 77 123 4567', 0, 1, 'R');
        $this->Cell(110);
        $this->Cell(80, 5, 'Email: info@powerwatch.lk', 0, 1, 'R');
        
        // Invoice title and details (Left Side)
        $this->SetY(15);
        $this->SetFont('Arial', 'B', 24);
        $this->SetTextColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->Cell(100, 10, 'INVOICE', 0, 1, 'L');
        
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(35, 6, 'Order No:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, $this->orderData['order_number'], 0, 1, 'L');
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(35, 6, 'Date:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, date('F j, Y, g:i a', strtotime($this->orderData['created_at'])), 0, 1, 'L');
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(35, 6, 'Payment Method:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, ucfirst($this->orderData['payment_method']), 0, 1, 'L');
        
        // Premium Gold Divider
        $this->SetY(55);
        $this->SetDrawColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->SetLineWidth(0.8);
        $this->Line(10, 55, 200, 55);
        
        $this->Ln(10);
    }
    
    // Page footer
    function Footer() {
        $this->SetY(-25);
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.2);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        
        $this->Ln(5);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 5, 'This is a computer-generated document. No signature is required.', 0, 1, 'C');
        $this->Cell(0, 5, 'Thank you for choosing Power Watch!', 0, 0, 'C');
    }
    
    // Customer info section (Fixed with MultiCell for line wrapping)
    function CustomerInfo() {
        $yStart = $this->GetY();
        
        // Headers
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->SetXY(10, $yStart);
        $this->Cell(90, 6, 'BILL TO', 0, 0, 'L');
        $this->SetXY(110, $yStart);
        $this->Cell(90, 6, 'SHIP TO', 0, 1, 'L');
        
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
        
        $yData = $this->GetY() + 2;
        
        // LEFT COLUMN (Bill To)
        $this->SetXY(10, $yData);
        $billName = $this->orderData['customer_fname'] . ' ' . $this->orderData['customer_lname'];
        $this->Cell(90, 5, $billName, 0, 1, 'L');
        $this->SetX(10);
        $this->MultiCell(85, 5, $this->orderData['shipping_address'], 0, 'L'); // Wraps long addresses safely
        $this->SetX(10);
        $this->Cell(90, 5, $this->orderData['shipping_city'], 0, 1, 'L');
        $this->SetX(10);
        $this->Cell(90, 5, 'Phone: ' . $this->orderData['customer_phone'], 0, 1, 'L');
        $leftY = $this->GetY(); // Save where the left column ended
        
        // RIGHT COLUMN (Ship To)
        $this->SetXY(110, $yData);
        $shipName = $this->orderData['customer_fname'] . ' ' . $this->orderData['customer_lname']; // Modify if you have separate ship names
        $this->Cell(90, 5, $shipName, 0, 1, 'L');
        $this->SetX(110);
        $this->MultiCell(85, 5, $this->orderData['shipping_address'], 0, 'L'); // Wraps long addresses safely
        $this->SetX(110);
        $this->Cell(90, 5, $this->orderData['shipping_city'], 0, 1, 'L');
        $this->SetX(110);
        $this->Cell(90, 5, 'Phone: ' . $this->orderData['customer_phone'], 0, 1, 'L');
        $rightY = $this->GetY(); // Save where the right column ended
        
        // Push the cursor below whichever column was longest
        $this->SetY(max($leftY, $rightY) + 10);
    }
    
    // Order items table (Fixed with Coordinate Mapping for wrapped text)
    function OrderItemsTable($items) {
        // Table Header
        $this->SetFillColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);
        
        $w = array(90, 20, 40, 40); // Total 190 (Perfect A4 Width)
        $this->Cell($w[0], 10, '  PRODUCT DESCRIPTION', 0, 0, 'L', true);
        $this->Cell($w[1], 10, 'QTY', 0, 0, 'C', true);
        $this->Cell($w[2], 10, 'UNIT PRICE', 0, 0, 'R', true);
        $this->Cell($w[3], 10, 'TOTAL  ', 0, 1, 'R', true);
        
        // Table Rows
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('Arial', '', 10);
        
        foreach($items as $item) {
            $startY = $this->GetY();
            
            // Add new page if we are too close to the bottom
            if ($startY > 250) {
                $this->AddPage();
                $startY = $this->GetY();
            }

            // Draw the wrapped product name FIRST
            $this->SetXY(10, $startY + 2);
            $this->MultiCell($w[0], 6, '  ' . $item['product_name'], 0, 'L');
            $endY = $this->GetY();
            
            // Calculate how tall this specific row needs to be based on the text wrap
            $rowHeight = max(10, $endY - $startY + 2); 
            
            // Draw the rest of the columns using the exact Y coordinate
            $this->SetXY(10 + $w[0], $startY + 2);
            $this->Cell($w[1], 6, $item['quantity'], 0, 0, 'C');
            $this->Cell($w[2], 6, 'LKR ' . number_format($item['product_price'], 2), 0, 0, 'R');
            $this->Cell($w[3], 6, 'LKR ' . number_format($item['item_total'], 2) . '  ', 0, 1, 'R');
            
            // Move cursor to bottom of the row and draw a clean bottom border
            $this->SetY($startY + $rowHeight);
            $this->SetDrawColor(230, 230, 230);
            $this->SetLineWidth(0.2);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
        }
        $this->Ln(8);
    }
    
    // Order summary
    function OrderSummary() {
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
        
        $this->Cell(120);
        $this->Cell(30, 6, 'Subtotal:', 0, 0, 'R');
        $this->Cell(40, 6, 'LKR ' . number_format($this->orderData['subtotal'], 2) . '  ', 0, 1, 'R');
        
        $this->Cell(120);
        $this->Cell(30, 6, 'Shipping:', 0, 0, 'R');
        $this->Cell(40, 6, 'LKR ' . number_format($this->orderData['shipping_cost'], 2) . '  ', 0, 1, 'R');
        
        // Total Box
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->SetTextColor(10, 17, 31);
        
        $this->Cell(120);
        $this->Cell(30, 10, 'TOTAL:', 0, 0, 'R', true);
        $this->Cell(40, 10, 'LKR ' . number_format($this->orderData['total_amount'], 2) . '  ', 0, 1, 'R', true);
        
        $this->Ln(15);
    }
    
    // Payment instructions
    function PaymentInstructions() {

        $mobileNumber = '0768863075';

        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->Cell(0, 8, 'PAYMENT ACTION REQUIRED', 0, 1, 'L');
        
        // Premium Bank Box
        $this->SetFillColor(248, 249, 250);
        $this->SetDrawColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->SetLineWidth(0.8);
        
        $yStart = $this->GetY();
        $this->Rect(10, $yStart, 190, 35, 'DF'); // D=Draw border, F=Fill background
        
        $this->SetY($yStart + 5);
        $this->SetTextColor(50, 50, 50);
        
        $this->SetX(15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, 'Bank Name:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'Bank of Ceylon', 0, 1, 'L');
        
        $this->SetX(15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, 'Branch:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'Minuwangoda Branch (545)', 0, 1, 'L');
        
        $this->SetX(15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, 'Account Name:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'MR R M S D RATNAYAKE', 0, 1, 'L');
        
        $this->SetX(15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, 'Account No:', 0, 0, 'L');
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->Cell(100, 6, '0003102670', 0, 1, 'L');
        
        $this->SetY($yStart + 40);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->MultiCell(0, 5, 'Please transfer the total amount and send the payment receipt via WhatsApp to ' . $mobileNumber . '. Include your Order No (' . $this->orderData['order_number'] . ') in the bank reference.', 0, 'L');
    }
}

// ===== MAIN EXECUTION =====
try {
    if(!isset($_GET['order']) || empty($_GET['order'])) {
        die('Order number is required');
    }
    
    $order_number = htmlspecialchars($_GET['order']);
    
    $stmt = Database::$connection->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->bind_param("s", $order_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        die('Order not found');
    }
    $orderData = $result->fetch_assoc();
    
    $stmt2 = Database::$connection->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt2->bind_param("i", $orderData['order_id']);
    $stmt2->execute();
    $items_result = $stmt2->get_result();
    
    $items = [];
    while($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }
    
    $pdf = new InvoicePDF($orderData);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->CustomerInfo();
    $pdf->OrderItemsTable($items);
    $pdf->OrderSummary();
    $pdf->PaymentInstructions();
    
    $filename = 'PowerWatch_Invoice_' . $order_number . '.pdf';
    $pdf->Output('I', $filename); // Switched to 'I' so it opens in the browser instantly instead of force-downloading blindly
    
} catch(Exception $e) {
    error_log("PDF Generation Error: " . $e->getMessage());
    die('Error generating PDF. Please try again later.');
}
?>