<?php
/**
 * Power Watch - PDF Invoice Generator
 * Server-side PDF generation with proper formatting
 * 
 * Usage: generate-invoice-pdf.php?order=PWORD7
 */

require_once '../../includes/connection.php';
require_once '../../includes/fpdf/fpdf.php'; // You'll need to download FPDF library

class InvoicePDF extends FPDF {
    private $orderData;
    
    // Colors
    private $darkBlue;
    private $gold;
    private $lightGray;
    
    function __construct($orderData) {
        parent::__construct();
        $this->orderData = $orderData;
        
        // Define colors (RGB)
        $this->darkBlue = array(10, 17, 31);
        $this->gold = array(212, 175, 55);
        $this->lightGray = array(248, 249, 250);
    }
    
    // Page header
    function Header() {
        // Logo area (if you have a logo)
        // $this->Image('logo.png', 10, 6, 30);
        
        // Power Watch branding
        $this->SetFont('Arial', 'B', 28);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->Cell(120);
        $this->Cell(30, 10, 'POWER WATCH', 0, 1, 'L');
        
        // Company details
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(120);
        $this->Cell(30, 5, 'No. 123, Main Street, Negombo', 0, 1, 'L');
        $this->Cell(120);
        $this->Cell(30, 5, 'Phone: +94 77 123 4567', 0, 1, 'L');
        $this->Cell(120);
        $this->Cell(30, 5, 'Email: info@powerwatch.lk', 0, 1, 'L');
        
        // Invoice title and details
        $this->SetY(10);
        $this->SetFont('Arial', 'B', 24);
        $this->SetTextColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->Cell(30, 10, 'INVOICE', 0, 1, 'L');
        
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(40, 6, 'Order No:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, $this->orderData['order_number'], 0, 1, 'L');
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, 'Date:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, date('F j, Y, g:i a', strtotime($this->orderData['created_at'])), 0, 1, 'L');
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, 'Payment Method:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, ucfirst($this->orderData['payment_method']), 0, 1, 'L');
        
        // Gold line separator
        $this->SetY(55);
        $this->SetDrawColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->SetLineWidth(0.8);
        $this->Line(10, 55, 200, 55);
        
        $this->Ln(5);
    }
    
    // Page footer
    function Footer() {
        $this->SetY(-25);
        
        // Line above footer
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.2);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        
        $this->Ln(3);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 5, 'This is a computer-generated document. No signature is required.', 0, 1, 'C');
        $this->Cell(0, 5, 'Thank you for choosing Power Watch!', 0, 0, 'C');
        
        // Page number
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
    
    // Customer info section
    function CustomerInfo() {
        $this->SetY(65);
        
        // Bill To section
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->Cell(95, 8, 'BILL TO', 0, 0, 'L');
        
        // Ship To section
        $this->Cell(95, 8, 'SHIP TO', 0, 1, 'L');
        
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
        
        // Billing address
        $billName = $this->orderData['customer_fname'] . ' ' . $this->orderData['customer_lname'];
        $this->Cell(95, 6, $billName, 0, 0, 'L');
        
        // Shipping address
        $shipName = $this->orderData['shipping_fname'] . ' ' . $this->orderData['shipping_lname'];
        $this->Cell(95, 6, $shipName, 0, 1, 'L');
        
        // Billing details
        $this->Cell(95, 6, $this->orderData['billing_address'], 0, 0, 'L');
        $this->Cell(95, 6, $this->orderData['shipping_address'], 0, 1, 'L');
        
        $billCity = $this->orderData['billing_city'] . ', ' . $this->orderData['billing_postal_code'];
        $shipCity = $this->orderData['shipping_city'] . ', ' . $this->orderData['shipping_postal_code'];
        
        $this->Cell(95, 6, $billCity, 0, 0, 'L');
        $this->Cell(95, 6, $shipCity, 0, 1, 'L');
        
        $this->Cell(95, 6, 'Phone: ' . $this->orderData['customer_phone'], 0, 0, 'L');
        $this->Cell(95, 6, 'Phone: ' . $this->orderData['shipping_phone'], 0, 1, 'L');
        
        $this->Cell(95, 6, 'Email: ' . $this->orderData['customer_email'], 0, 1, 'L');
        
        $this->Ln(8);
    }
    
    // Order items table
    function OrderItemsTable($items) {
        // Table header
        $this->SetFillColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->SetLineWidth(0.3);
        $this->SetFont('Arial', 'B', 10);
        
        // Column widths
        $w = array(80, 30, 30, 50);
        $headers = array('PRODUCT', 'QUANTITY', 'UNIT PRICE', 'TOTAL');
        
        for($i = 0; $i < count($headers); $i++) {
            $this->Cell($w[$i], 10, $headers[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Table data
        $this->SetFillColor(248, 249, 250);
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('Arial', '', 10);
        
        $fill = false;
        foreach($items as $item) {
            $this->Cell($w[0], 8, $item['product_name'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 8, $item['quantity'], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 8, 'LKR ' . number_format($item['unit_price'], 2), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 8, 'LKR ' . number_format($item['item_total'], 2), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln(5);
    }
    
    // Order summary (totals)
    function OrderSummary() {
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
        
        // Position from right
        $this->Cell(140);
        $this->Cell(25, 6, 'Subtotal:', 0, 0, 'L');
        $this->Cell(25, 6, 'LKR ' . number_format($this->orderData['subtotal'], 2), 0, 1, 'R');
        
        $this->Cell(140);
        $this->Cell(25, 6, 'Shipping:', 0, 0, 'L');
        $this->Cell(25, 6, 'LKR ' . number_format($this->orderData['shipping_cost'], 2), 0, 1, 'R');
        
        if($this->orderData['discount_amount'] > 0) {
            $this->SetTextColor(0, 150, 0);
            $this->Cell(140);
            $this->Cell(25, 6, 'Discount:', 0, 0, 'L');
            $this->Cell(25, 6, '- LKR ' . number_format($this->orderData['discount_amount'], 2), 0, 1, 'R');
        }
        
        // Total with gold background
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140);
        $this->Cell(25, 10, 'TOTAL:', 1, 0, 'L', true);
        $this->Cell(25, 10, 'LKR ' . number_format($this->orderData['total_amount'], 2), 1, 1, 'R', true);
        
        $this->Ln(8);
    }
    
    // Payment instructions
    function PaymentInstructions() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->darkBlue[0], $this->darkBlue[1], $this->darkBlue[2]);
        $this->Cell(0, 8, 'PAYMENT INFORMATION', 0, 1, 'L');
        
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
        
        // Bank details box
        $this->SetFillColor(245, 247, 250);
        $this->SetDrawColor(212, 175, 55);
        $this->SetLineWidth(0.5);
        
        $y = $this->GetY();
        $this->Rect(10, $y, 190, 40, 'D');
        
        $this->SetY($y + 5);
        $this->SetX(15);
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 6, 'Bank Name:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'Bank of Ceylon', 0, 1, 'L');
        $this->SetX(15);
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 6, 'Branch:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'Minuwangoda Branch (545)', 0, 1, 'L');
        $this->SetX(15);
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 6, 'Account Holder:', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'MR R M S D RATNAYAKE', 0, 1, 'L');
        $this->SetX(15);
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 6, 'Account Number:', 0, 0, 'L');
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->gold[0], $this->gold[1], $this->gold[2]);
        $this->Cell(100, 6, '0003102670', 0, 1, 'L');
        
        $this->Ln(10);
        
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->MultiCell(0, 5, 'Please transfer the total amount to the above bank account and send the payment receipt via WhatsApp to +94 77 123 4567 or email to payments@powerwatch.lk', 0, 'L');
    }
}

// ===== MAIN EXECUTION =====

try {
    // Get order number from URL
    if(!isset($_GET['order']) || empty($_GET['order'])) {
        die('Order number is required');
    }
    
    $order_number = htmlspecialchars($_GET['order']);
    
    // Fetch order data
    $stmt = Database::$connection->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->bind_param("s", $order_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        die('Order not found');
    }
    
    $orderData = $result->fetch_assoc();
    
    // Fetch order items
    $stmt2 = Database::$connection->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt2->bind_param("i", $orderData['order_id']);
    $stmt2->execute();
    $items_result = $stmt2->get_result();
    
    $items = [];
    while($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }
    
    // Create PDF
    $pdf = new InvoicePDF($orderData);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->CustomerInfo();
    $pdf->OrderItemsTable($items);
    $pdf->OrderSummary();
    $pdf->PaymentInstructions();
    
    // Output PDF
    $filename = 'PowerWatch_Invoice_' . $order_number . '.pdf';
    $pdf->Output('D', $filename); // 'D' = force download, 'I' = display in browser
    
} catch(Exception $e) {
    error_log("PDF Generation Error: " . $e->getMessage());
    die('Error generating PDF. Please try again later.');
}
?>
