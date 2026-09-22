<?php
session_start();
require 'vendor/autoload.php';

// Safe check to avoid halting if FPDF wasn't downloaded properly yet
if (!file_exists('fpdf.php')) {
    die("<div style='background:#161920; color:#fff; padding:30px; font-family:sans-serif; border-radius:8px; margin:50px auto; max-width:600px; border:1px solid #ff4a5a;'>
            <h3 style='color:#ff4a5a;'>FPDF Library Missing</h3>
            <p>Please download FPDF from fpdf.org and extract <b>fpdf.php</b> and the <b>font/</b> folder directly into: <code>C:\\xampp\\htdocs\\PowerRig\\</code></p>
         </div>");
}

require 'fpdf.php';
include "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Your Stripe Secret Key (Test Mode)
\Stripe\Stripe::setApiKey('sk_test_51TktYCHoQmFn3lOsaVoDT0L00RAXZHjrGAKnK0fxpTjQRwFs5tdyi8BLjbJrZBqwQXcyP6uIxokJcmMJ5fCfAslR00AMaPcuPv');

if (!isset($_GET['session_id']) || !isset($_SESSION["user"])) {
    header("Location: home.php");
    exit();
}

$session_id = $_GET['session_id'];
$user_email = $_SESSION["user"]["email"];

try {
    // Retrieve the checkout session along with its line items itemized from Stripe
    $session = \Stripe\Checkout\Session::retrieve([
        'id' => $session_id,
        'expand' => ['line_items']
    ]);
    
    if ($session->payment_status == 'paid') {
        
        $total_paid = $session->amount_total / 100;
        
        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date_time = $d->format("Y-m-d H:i:s");

        // Fetch user address bindings safely
        $address_id = 0;
        $address_rs = Database::search("SELECT `address_address_id` FROM `user_has_address` WHERE `user_email`='" . addslashes($user_email) . "'");
        if ($address_rs && $address_rs->num_rows > 0) {
            $address_id = $address_rs->fetch_assoc()["address_address_id"];
        }

        // Generate Structural Keys
        $order_id = "ORD-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $invoice_id = "INV-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // 1. Insert Master Order record
        $total_items_count = count($session->line_items->data);
        Database::iud("INSERT INTO `order` (`order_id`, `order_date`, `total_amount`, `order_status_id`, `shipping_address`, `billing_address`, `user_email`, `qty`) 
                       VALUES ('".$order_id."', '".$date_time."', '".$total_paid."', '1', '".$address_id."', '".$address_id."', '".addslashes($user_email)."', '".$total_items_count."')");

        // 2. Insert Master Invoice record
        Database::iud("INSERT INTO `invoice` (`invoice_id`, `order_order_id`, `invoice_date`, `total_price`) 
                       VALUES ('".$invoice_id."', '".$order_id."', '".$date_time."', '".$total_paid."')");

        // =========================================================================
        // PDF BRAND PALETTE (mirrors PowerRig dark theme: #0f1115 / #161920 / #ff4a5a)
        // =========================================================================
        // FPDF cannot render true dark backgrounds reliably across a printable
        // multi-page document, so the brand is carried through a dark header band,
        // a coral accent rule, and coral/charcoal accents on a clean white body —
        // the same approach Stripe/Apple use for dark-themed brands on invoices.
        $c_bg_dark    = [22, 25, 32];     // --pd-surface  #161920
        $c_ink        = [15, 17, 21];     // --pd-bg       #0f1115
        $c_accent     = [255, 74, 90];    // --pd-accent   #ff4a5a
        $c_accent_drk = [224, 59, 74];    // --pd-accent-dark
        $c_text_dim   = [120, 126, 145];  // muted slate (print-safe vs --pd-text-dim)
        $c_text_body  = [55, 60, 72];     // body copy, dark slate (better print contrast)
        $c_border     = [224, 226, 232];  // hairline borders
        $c_row_alt    = [247, 248, 250];  // zebra striping
        $c_white      = [255, 255, 255];

        // Initialize FPDF Setup
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 22);
        $pageW = 210;
        $marginL = 10;
        $marginR = 10;
        $contentW = $pageW - $marginL - $marginR; // 190

        // -------------------------------------------------------------------
        // DARK BRAND HEADER BAND
        // -------------------------------------------------------------------
        $pdf->SetFillColor($c_bg_dark[0], $c_bg_dark[1], $c_bg_dark[2]);
        $pdf->Rect(0, 0, $pageW, 38, 'F');

        // Coral accent rule under the header band (mirrors the site's gradient sweep line)
        $pdf->SetFillColor($c_accent[0], $c_accent[1], $c_accent[2]);
        $pdf->Rect(0, 38, $pageW, 1.6, 'F');

        // Wordmark
        $pdf->SetXY($marginL, 11);
        $pdf->SetTextColor($c_white[0], $c_white[1], $c_white[2]);
        $pdf->SetFont('Arial', 'B', 22);
        $pdf->Cell(120, 10, 'POWERRIG', 0, 0, 'L');

        // Coral "ELECTRONICS" suffix for two-tone wordmark, like the site's logo
        $pdf->SetTextColor($c_accent[0], $c_accent[1], $c_accent[2]);
        $pdf->SetFont('Arial', 'B', 22);
        $markWidth = $pdf->GetStringWidth('POWERRIG ');
        $pdf->SetXY($marginL + $markWidth, 11);
        $pdf->Cell(70, 10, 'ELECTRONICS', 0, 0, 'L');

        $pdf->SetXY($marginL, 22);
        $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(120, 5, 'Premium PC Components & Gaming Gear Store', 0, 0, 'L');

        // "PAID" status pill, top-right of header band
        $pdf->SetXY($pageW - $marginR - 38, 13);
        $pdf->SetFillColor($c_accent[0], $c_accent[1], $c_accent[2]);
        $pdf->SetTextColor($c_white[0], $c_white[1], $c_white[2]);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(38, 9, 'PAID', 0, 0, 'C', true);

        $pdf->SetXY($marginL, 46);
        $pdf->SetTextColor(0, 0, 0);

        // -------------------------------------------------------------------
        // INVOICE TITLE + METADATA BLOCK
        // -------------------------------------------------------------------
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor($c_ink[0], $c_ink[1], $c_ink[2]);
        $pdf->Cell(95, 9, 'INVOICE', 0, 0, 'L');

        // Right-aligned metadata card background
        $pdf->SetFillColor($c_row_alt[0], $c_row_alt[1], $c_row_alt[2]);
        $pdf->Rect($marginL + 95, 46, 95, 28, 'F');

        $pdf->SetXY($marginL + 95, 49);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
        $pdf->Cell(40, 5.5, 'INVOICE NUMBER', 0, 0, 'L');
        $pdf->Cell(55, 5.5, 'DATE ISSUED', 0, 1, 'R');

        $pdf->SetX($marginL + 95);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor($c_ink[0], $c_ink[1], $c_ink[2]);
        $pdf->Cell(40, 6.5, $invoice_id, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(55, 6.5, $d->format("Y-m-d"), 0, 1, 'R');

        $pdf->SetX($marginL + 95);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
        $pdf->Cell(40, 5.5, 'ORDER ID', 0, 0, 'L');
        $pdf->Cell(55, 5.5, 'TIME', 0, 1, 'R');

        $pdf->SetX($marginL + 95);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor($c_ink[0], $c_ink[1], $c_ink[2]);
        $pdf->Cell(40, 6, $order_id, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(55, 6, $d->format("H:i:s"), 0, 1, 'R');

        // "Billed to" block under INVOICE title
        $pdf->SetXY($marginL, 58);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
        $pdf->Cell(95, 5, 'BILLED TO', 0, 1, 'L');
        $pdf->SetX($marginL);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor($c_ink[0], $c_ink[1], $c_ink[2]);
        $pdf->Cell(95, 6, $user_email, 0, 1, 'L');

        $pdf->SetY(82);

        // -------------------------------------------------------------------
        // ITEMS TABLE
        // -------------------------------------------------------------------
        $colDesc = 95;
        $colUnit = 28;
        $colQty  = 17;
        $colSub  = 50;

        // Dark header row (mirrors the site's surface color)
        $pdf->SetX($marginL);
        $pdf->SetFillColor($c_bg_dark[0], $c_bg_dark[1], $c_bg_dark[2]);
        $pdf->SetTextColor($c_white[0], $c_white[1], $c_white[2]);
        $pdf->SetFont('Arial', 'B', 9.5);
        $pdf->Cell($colDesc, 10, '  ITEM DESCRIPTION', 0, 0, 'L', true);
        $pdf->Cell($colUnit, 10, 'UNIT PRICE', 0, 0, 'C', true);
        $pdf->Cell($colQty, 10, 'QTY', 0, 0, 'C', true);
        $pdf->Cell($colSub, 10, 'SUBTOTAL (LKR)  ', 0, 1, 'R', true);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor($c_text_body[0], $c_text_body[1], $c_text_body[2]);
        $row_index = 0;

        // Tracking data payload bindings for HTML emails
        $email_items_rows_html = "";

        // Loop over line items extracted from the Stripe Session payload
        foreach ($session->line_items->data as $item) {
            $product_title = $item->description;
            $bought_qty = $item->quantity;
            $item_subtotal = $item->amount_total / 100;
            $unit_price = $item_subtotal / $bought_qty;

            // Skip processing into invoice_items if this line item is the Delivery Fee entry
            if ($product_title == 'Shipping & Delivery Fee') {
                $pdf->SetX($marginL);
                $rowFill = ($row_index % 2 === 0) ? $c_white : $c_row_alt;
                $pdf->SetFillColor($rowFill[0], $rowFill[1], $rowFill[2]);
                $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell($colDesc, 10, '  ' . $product_title, 'T', 0, 'L', true);
                $pdf->Cell($colUnit, 10, number_format($item_subtotal, 2), 'T', 0, 'C', true);
                $pdf->Cell($colQty, 10, $bought_qty, 'T', 0, 'C', true);
                $pdf->Cell($colSub, 10, number_format($item_subtotal, 2) . '  ', 'T', 1, 'R', true);
                $pdf->SetDrawColor($c_border[0], $c_border[1], $c_border[2]);
                $row_index++;

                $email_items_rows_html .= '
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px solid #1e222b; color: #a0a5b5; font-style: italic;">'.htmlspecialchars($product_title).'</td>
                        <td style="padding: 12px 0; border-bottom: 1px solid #1e222b; text-align: center; color: #a0a5b5;">'.$bought_qty.'</td>
                        <td style="padding: 12px 0; border-bottom: 1px solid #1e222b; text-align: right; color: #a0a5b5;">Rs. '.number_format($item_subtotal, 2).'</td>
                    </tr>';
                continue;
            }

            // Resolve matching product record ID via its explicit Title mapping parameters
            $escaped_title = addslashes($product_title);
            $product_rs = Database::search("SELECT * FROM `product` WHERE `title` = '".$escaped_title."' LIMIT 1");
            
            if ($product_rs && $product_rs->num_rows > 0) {
                $product_data = $product_rs->fetch_assoc();
                $product_id = $product_data['id'];
                
                // Drop stock count allocations accurately
                $new_qty = intval($product_data['qty']) - $bought_qty;
                Database::iud("UPDATE `product` SET `qty`='".$new_qty."' WHERE `id`='".$product_id."'");

                // 3. Insert individual item records securely into invoice_item mapping links
                Database::iud("INSERT INTO `invoice_item` (`invoice_invoice_id`, `product_id`, `product_qty`) 
                               VALUES ('".$invoice_id."', '".$product_id."', '".$bought_qty."')");
            }

            // Append item line directly to FPDF — zebra striped, brand-aligned
            $pdf->SetX($marginL);
            $rowFill = ($row_index % 2 === 0) ? $c_white : $c_row_alt;
            $pdf->SetFillColor($rowFill[0], $rowFill[1], $rowFill[2]);
            $pdf->SetTextColor($c_text_body[0], $c_text_body[1], $c_text_body[2]);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell($colDesc, 10, '  ' . substr($product_title, 0, 44), 'T', 0, 'L', true);
            $pdf->Cell($colUnit, 10, number_format($unit_price, 2), 'T', 0, 'C', true);
            $pdf->Cell($colQty, 10, $bought_qty, 'T', 0, 'C', true);
            $pdf->Cell($colSub, 10, number_format($item_subtotal, 2) . '  ', 'T', 1, 'R', true);
            $pdf->SetDrawColor($c_border[0], $c_border[1], $c_border[2]);
            $row_index++;

            // Append clean email line item data blocks (ellipsis only when actually truncated)
            $display_title = (strlen($product_title) > 40) ? substr($product_title, 0, 40) . '…' : $product_title;
            $email_items_rows_html .= '
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #1e222b; color: #ffffff;">'.htmlspecialchars($display_title).'</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #1e222b; text-align: center; color: #a0a5b5;">'.$bought_qty.'</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #1e222b; text-align: right; color: #ffffff;">Rs. '.number_format($item_subtotal, 2).'</td>
                </tr>';
        }

        // -------------------------------------------------------------------
        // GRAND TOTAL BAND (dark, brand-matched — echoes the buybox price row)
        // -------------------------------------------------------------------
        $pdf->SetX($marginL);
        $pdf->SetFillColor($c_bg_dark[0], $c_bg_dark[1], $c_bg_dark[2]);
        $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell($colDesc + $colUnit + $colQty, 13, 'GRAND TOTAL  ', 0, 0, 'R', true);
        $pdf->SetTextColor($c_accent[0], $c_accent[1], $c_accent[2]);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell($colSub, 13, 'Rs. ' . number_format($total_paid, 2) . '  ', 0, 1, 'R', true);

        // -------------------------------------------------------------------
        // FOOTER NOTE
        // -------------------------------------------------------------------
        $pdf->Ln(14);
        $pdf->SetX($marginL);
        $pdf->SetDrawColor($c_accent[0], $c_accent[1], $c_accent[2]);
        $pdf->SetLineWidth(0.6);
        $pdf->Line($marginL, $pdf->GetY(), $marginL + 26, $pdf->GetY());
        $pdf->Ln(4);

        $pdf->SetX($marginL);
        $pdf->SetFont('Arial', 'B', 10.5);
        $pdf->SetTextColor($c_ink[0], $c_ink[1], $c_ink[2]);
        $pdf->Cell($contentW, 6, 'Thank you for shopping with PowerRig Electronics.', 0, 1, 'L');

        $pdf->SetX($marginL);
        $pdf->SetFont('Arial', '', 9.5);
        $pdf->SetTextColor($c_text_dim[0], $c_text_dim[1], $c_text_dim[2]);
        $pdf->Cell($contentW, 5.5, 'This document confirms your order and serves as an official record of payment.', 0, 1, 'L');
        $pdf->SetX($marginL);
        $pdf->Cell($contentW, 5.5, 'For warranty claims or order support, contact us with the Invoice Number above.', 0, 1, 'L');

        $pdf->SetLineWidth(0.2);

        // Save generated tracking file inside server directory
        if (!file_exists('invoices')) {
            mkdir('invoices', 0777, true);
        }
        $pdf_path = "invoices/" . $invoice_id . ".pdf";
        $pdf->Output('F', $pdf_path);

        // Clear session cart state and empty database cart rows upon validation
        if (isset($_SESSION["cart"])) {
            unset($_SESSION["cart"]);
        }
        Database::iud("DELETE FROM `cart` WHERE `user_email` = '".addslashes($user_email)."'");

        // =========================================================================
        // SMTP DISPATCH SYSTEM
        // =========================================================================
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                 
            $mail->Username   = 'isururathnayaka999@gmail.com';               
            $mail->Password   = 'lbzbrhlfonbxucee';                  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       
            $mail->Port       = 587;                                  

            $mail->setFrom('isururathnayaka999@gmail.com', 'PowerRig Electronics');
            $mail->addAddress($user_email);                           
            $mail->addAttachment($pdf_path, $invoice_id . ".pdf");

            $mail->isHTML(true);
            $mail->Subject = 'Your PowerRig Order Confirmation - ' . $order_id;
            $mail->Body    = '
                <!--[if mso]><table role="presentation" width="100%"><tr><td><![endif]-->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0a0c10; padding:32px 16px;">
                    <tr>
                        <td align="center">
                        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#0f1115; border:1px solid rgba(255,255,255,0.06); border-radius:12px; overflow:hidden; font-family:\'Segoe UI\', Helvetica, Arial, sans-serif;">

                            <!-- Header band -->
                            <tr>
                                <td style="background-color:#161920; padding:32px 36px 24px; border-bottom:3px solid #ff4a5a;" align="center">
                                    <span style="font-size:24px; font-weight:800; letter-spacing:-0.3px;"><span style="color:#ffffff;">POWERRIG</span><span style="color:#ff4a5a;"> ELECTRONICS</span></span>
                                    <p style="color:#a0a5b5; margin:6px 0 0; font-size:12.5px; letter-spacing:0.3px;">Premium PC Components &amp; Gaming Gear Store</p>
                                </td>
                            </tr>

                            <!-- Status strip -->
                            <tr>
                                <td style="padding:22px 36px 0;">
                                    <table role="presentation" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="background-color:rgba(40,167,69,0.14); border:1px solid rgba(40,167,69,0.3); border-radius:999px; padding:5px 14px;">
                                                <span style="color:#28a745; font-size:12px; font-weight:700; letter-spacing:0.4px;">&#10003; PAYMENT CONFIRMED</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Greeting -->
                            <tr>
                                <td style="padding:18px 36px 4px;">
                                    <p style="font-size:17px; color:#ffffff; margin:0 0 8px; font-weight:600;">Thanks for your order</p>
                                    <p style="font-size:14px; line-height:1.6; color:#a0a5b5; margin:0;">Your payment has been verified and the following items have been allocated from our inventory for shipment.</p>
                                </td>
                            </tr>

                            <!-- Items table -->
                            <tr>
                                <td style="padding:20px 36px 0;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                                        <thead>
                                            <tr style="text-align:left; color:#a0a5b5;">
                                                <th align="left" style="padding-bottom:10px; border-bottom:1px solid #1e222b; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Item</th>
                                                <th align="center" style="padding-bottom:10px; border-bottom:1px solid #1e222b; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Qty</th>
                                                <th align="right" style="padding-bottom:10px; border-bottom:1px solid #1e222b; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            '.$email_items_rows_html.'
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <!-- Order summary card -->
                            <tr>
                                <td style="padding:24px 36px 0;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#161920; border:1px solid rgba(255,255,255,0.05); border-radius:10px;">
                                        <tr>
                                            <td style="padding:18px 20px 6px;">
                                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13.5px; color:#ffffff;">
                                                    <tr><td style="padding:5px 0; color:#a0a5b5;">Order ID</td><td align="right" style="padding:5px 0; font-weight:700; font-family:\'Courier New\',monospace;">'.$order_id.'</td></tr>
                                                    <tr><td style="padding:5px 0; color:#a0a5b5;">Invoice Reference</td><td align="right" style="padding:5px 0; font-weight:700; font-family:\'Courier New\',monospace;">'.$invoice_id.'</td></tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 20px 18px; border-top:1px solid rgba(255,255,255,0.06);">
                                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="padding-top:10px; color:#a0a5b5; font-size:14px;">Total Settled</td>
                                                        <td align="right" style="padding-top:10px; font-size:19px; font-weight:800; color:#ff4a5a;">Rs. '.number_format($total_paid, 2).'</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- CTA -->
                            <tr>
                                <td style="padding:26px 36px 4px;" align="center">
                                    <table role="presentation" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="background-color:#ff4a5a; border-radius:8px;">
                                                <span style="display:inline-block; padding:13px 28px; font-size:14px; font-weight:700; color:#ffffff;">&#128206; Invoice Attached Below</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:14px 36px 28px;">
                                    <p style="font-size:12.5px; color:#6b7280; line-height:1.6; margin:0; text-align:center;">The complete itemized invoice — including handling policy and warranty terms — is attached to this email as a PDF.</p>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style="padding:20px 36px; border-top:1px solid #1e222b; background-color:#0c0e12;" align="center">
                                    <p style="margin:0; color:#6b7280; font-size:11px;">&copy; '.date("Y").' PowerRig Electronics. All rights reserved.</p>
                                    <p style="margin:6px 0 0; color:#4b5160; font-size:10.5px;">This is an automated confirmation — please do not reply directly to this email.</p>
                                </td>
                            </tr>

                        </table>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->';

            $mail->send();
            $email_sent_status = true;
        } catch (Exception $e) {
            $email_sent_status = false;
        }

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Order Confirmed | PowerRig</title>
            <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="bootstrap.css">
            <link rel="stylesheet" href="home.css">
            <style>
                :root {
                    --sx-bg: #0f1115;
                    --sx-surface: #161920;
                    --sx-surface-raised: #1c202a;
                    --sx-input: #1e222b;
                    --sx-border: rgba(255, 255, 255, 0.08);
                    --sx-border-soft: rgba(255, 255, 255, 0.05);
                    --sx-text: #ffffff;
                    --sx-text-dim: #a0a5b5;
                    --sx-accent: #ff4a5a;
                    --sx-accent-dark: #e03b4a;
                    --sx-accent-light: #ff7c87;
                    --sx-accent-soft: rgba(255, 74, 90, 0.14);
                    --sx-success: #28a745;
                    --sx-success-soft: rgba(40, 167, 69, 0.14);
                    --sx-radius-sm: 6px;
                    --sx-radius-md: 12px;
                    --sx-radius-lg: 20px;
                    --sx-font-display: 'Urbanist', sans-serif;
                    --sx-font-mono: 'JetBrains Mono', 'Space Mono', monospace;
                    --sx-ease: cubic-bezier(0.16, 0.84, 0.44, 1);
                }
                body.sx-body { background-color: var(--sx-bg) !important; color: var(--sx-text); font-family: var(--sx-font-display); margin: 0; }
                #sx-root * { box-sizing: border-box; }
                #sx-root .sx-mono { font-family: var(--sx-font-mono); font-variant-numeric: tabular-nums; }

                .sx-wrap {
                    min-height: calc(100vh - 80px);
                    display: flex;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 56px 20px 64px;
                }

                .sx-card {
                    width: 100%;
                    max-width: 620px;
                    background-color: var(--sx-surface);
                    border: 1px solid var(--sx-border-soft);
                    border-radius: var(--sx-radius-lg);
                    box-shadow: 0 24px 60px -30px rgba(0, 0, 0, 0.65);
                    overflow: hidden;
                    position: relative;
                }

                .sx-card::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 3px;
                    background: linear-gradient(90deg, transparent, var(--sx-accent) 22%, var(--sx-accent-light) 50%, var(--sx-accent) 78%, transparent);
                }

                .sx-head {
                    text-align: center;
                    padding: 46px 36px 26px;
                }

                .sx-check {
                    width: 64px;
                    height: 64px;
                    margin: 0 auto 18px;
                    border-radius: 50%;
                    background: var(--sx-success-soft);
                    border: 1px solid rgba(40, 167, 69, 0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 30px;
                    color: var(--sx-success);
                }

                .sx-title {
                    font-size: 1.7rem;
                    font-weight: 800;
                    letter-spacing: -0.4px;
                    margin: 0 0 8px;
                    color: var(--sx-text);
                }

                .sx-sub {
                    color: var(--sx-text-dim);
                    font-size: 0.98rem;
                    line-height: 1.55;
                    margin: 0 auto;
                    max-width: 420px;
                }

                .sx-mail-note {
                    margin: 22px 36px 0;
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    background: var(--sx-success-soft);
                    border: 1px solid rgba(40, 167, 69, 0.25);
                    color: #6fd394;
                    font-size: 0.88rem;
                    line-height: 1.5;
                    border-radius: var(--sx-radius-sm);
                    padding: 12px 14px;
                    text-align: left;
                }

                .sx-mail-note ion-icon { font-size: 1.15rem; flex-shrink: 0; margin-top: 1px; }
                .sx-mail-note b { color: #ffffff; }

                .sx-divider {
                    border: 0;
                    height: 1px;
                    background: var(--sx-border-soft);
                    margin: 26px 36px;
                }

                .sx-ids {
                    margin: 0 36px;
                    display: flex;
                    gap: 12px;
                }

                .sx-id-chip {
                    flex: 1;
                    background: var(--sx-input);
                    border: 1px solid var(--sx-border);
                    border-radius: var(--sx-radius-sm);
                    padding: 12px 14px;
                }

                .sx-id-label {
                    display: block;
                    font-size: 0.72rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: var(--sx-text-dim);
                    margin-bottom: 4px;
                    font-weight: 600;
                }

                .sx-id-value {
                    font-family: var(--sx-font-mono);
                    font-size: 0.92rem;
                    font-weight: 600;
                    color: var(--sx-text);
                    word-break: break-all;
                }

                .sx-items-label {
                    margin: 26px 36px 10px;
                    font-size: 0.78rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                    color: var(--sx-text-dim);
                }

                .sx-items {
                    margin: 0 36px;
                    max-height: 220px;
                    overflow-y: auto;
                    border-top: 1px solid var(--sx-border-soft);
                    scrollbar-width: thin;
                }

                .sx-item-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 12px;
                    padding: 12px 0;
                    border-bottom: 1px solid var(--sx-border-soft);
                    font-size: 0.92rem;
                }

                .sx-item-name {
                    color: var(--sx-text-dim);
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                .sx-item-qty {
                    color: var(--sx-accent);
                    font-weight: 700;
                    margin-left: 6px;
                }

                .sx-item-price {
                    color: var(--sx-text);
                    font-weight: 600;
                    font-family: var(--sx-font-mono);
                    flex-shrink: 0;
                }

                .sx-total-row {
                    margin: 18px 36px 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: baseline;
                    padding-top: 16px;
                    border-top: 1px dashed var(--sx-border);
                }

                .sx-total-label {
                    color: var(--sx-text-dim);
                    font-size: 0.95rem;
                }

                .sx-total-value {
                    font-family: var(--sx-font-mono);
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: var(--sx-accent);
                }

                .sx-actions {
                    display: flex;
                    gap: 10px;
                    padding: 30px 36px 36px;
                }

                .sx-btn {
                    flex: 1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    padding: 13px 16px;
                    font-size: 0.98rem;
                    font-weight: 700;
                    border-radius: var(--sx-radius-sm);
                    cursor: pointer;
                    border: none;
                    font-family: inherit;
                    text-decoration: none;
                    position: relative;
                    overflow: hidden;
                    transition: transform 0.15s var(--sx-ease), background-color 0.2s var(--sx-ease), box-shadow 0.2s var(--sx-ease);
                }

                .sx-btn:active { transform: scale(0.97); }

                .sx-btn-primary {
                    background-color: var(--sx-accent);
                    color: #ffffff;
                }
                .sx-btn-primary:hover {
                    background-color: var(--sx-accent-dark);
                    box-shadow: 0 16px 40px -16px rgba(255, 74, 90, 0.38);
                }

                .sx-btn-ghost {
                    background-color: var(--sx-input);
                    color: var(--sx-text);
                    border: 1px solid var(--sx-border);
                }
                .sx-btn-ghost:hover {
                    background-color: var(--sx-surface-raised);
                    border-color: rgba(255, 255, 255, 0.16);
                }

                @media (max-width: 480px) {
                    .sx-head, .sx-mail-note, .sx-ids, .sx-items-label, .sx-items, .sx-total-row, .sx-actions { margin-left: 22px; margin-right: 22px; }
                    .sx-head { padding-left: 0; padding-right: 0; }
                    .sx-divider { margin-left: 22px; margin-right: 22px; }
                    .sx-ids { flex-direction: column; gap: 10px; }
                    .sx-actions { flex-direction: column; }
                }
            </style>
        </head>
        <body class="sx-body" id="sx-root">
            <div class="header-global-container">
                <?php include "header.php"; ?>
            </div>

            <div class="sx-wrap">
                <div class="sx-card">

                    <div class="sx-head">
                        <div class="sx-check">&#10004;</div>
                        <h1 class="sx-title">Order Confirmed</h1>
                        <p class="sx-sub">Payment captured successfully. Your gear is being prepped for dispatch.</p>
                    </div>

                    <?php if (isset($email_sent_status) && $email_sent_status): ?>
                        <div class="sx-mail-note">
                            <ion-icon name="mail-outline"></ion-icon>
                            <span><b>Confirmation sent.</b> An itemized invoice was emailed to <b><?php echo htmlspecialchars($user_email); ?></b>.</span>
                        </div>
                    <?php else: ?>
                        <div class="sx-mail-note" style="background: var(--sx-accent-soft); border-color: rgba(255,74,90,0.3); color: #ffb3ba;">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                            <span><b>Order placed</b>, but we couldn't email your confirmation right now. You can still download the invoice below.</span>
                        </div>
                    <?php endif; ?>

                    <hr class="sx-divider">

                    <div class="sx-ids">
                        <div class="sx-id-chip">
                            <span class="sx-id-label">Order ID</span>
                            <span class="sx-id-value"><?php echo htmlspecialchars($order_id); ?></span>
                        </div>
                        <div class="sx-id-chip">
                            <span class="sx-id-label">Invoice Reference</span>
                            <span class="sx-id-value"><?php echo htmlspecialchars($invoice_id); ?></span>
                        </div>
                    </div>

                    <div class="sx-items-label">Purchased Items</div>
                    <div class="sx-items">
                        <?php
                        foreach ($session->line_items->data as $item) {
                            echo '<div class="sx-item-row">
                                    <span class="sx-item-name">'.htmlspecialchars($item->description).'<span class="sx-item-qty">×'.$item->quantity.'</span></span>
                                    <span class="sx-item-price sx-mono">Rs. '.number_format(($item->amount_total/100), 2).'</span>
                                  </div>';
                        }
                        ?>
                    </div>

                    <div class="sx-total-row">
                        <span class="sx-total-label">Total Paid</span>
                        <span class="sx-total-value sx-mono">Rs. <?php echo number_format($total_paid, 2); ?></span>
                    </div>

                    <div class="sx-actions">
                        <a href="<?php echo htmlspecialchars($pdf_path); ?>" class="sx-btn sx-btn-primary" download>
                            <ion-icon name="download-outline"></ion-icon> Download Invoice
                        </a>
                        <a href="home.php" class="sx-btn sx-btn-ghost">
                            <ion-icon name="storefront-outline"></ion-icon> Continue Shopping
                        </a>
                    </div>

                </div>
            </div>

            <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "Payment validation sequence failed parameters check.";
    }
} catch (Exception $e) {
    echo "Processing Exception: " . $e->getMessage();
}
?>