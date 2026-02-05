<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - Dulyaana Bathik</title>
    <style>
        @page { size: A5; margin: 0; }
        body { margin: 0; padding: 0; background-color: #fff; font-family: 'Roboto', Arial, sans-serif; }
        
        .label-wrapper {
            width: 148mm;
            /* height: 210mm; */
            height: auto;
            margin: 0 auto;
            padding: 12mm;
            border: 2px solid #000;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .brand-name { font-size: 30px; font-weight: 900; margin: 0; letter-spacing: 1px; }
        .order-id { font-size: 18px; font-weight: 700; margin-top: 5px; }

        /* Two Column Address Layout */
        .address-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .address-column {
            flex: 1;
        }

        .section-title { 
            font-size: 12px; 
            font-weight: 700; 
            text-transform: uppercase; 
            color: #666; 
            margin-bottom: 8px; 
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }

        .address-text { font-size: 15px; line-height: 1.4; }
        .contact-details{ font-size: 15px; line-height: 1.4; margin-top: 10px; font-size: 14px;}
        .ship-to-name { font-size: 20px; font-weight: 800; display: block; margin-bottom: 5px; }

        /* Mid-Section Details */
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 15px;
        }

        .method-row-inner {
            display: flex;
            align-items: center; /* Vertically centers the badge with the bar */
            gap: 10px;           /* Space between the bar and the badge */
        }

        .method-bar {
            background: #000; 
            color: #fff; 
            padding: 12px;
            font-weight: 700; 
            text-align: center; 
            font-size: 18px; 
            flex-grow: 1;        /* Allows method bar to fill available space */
        }

        .status-badge {
            padding: 10px 12px;  /* Adjusted padding for single-line height */
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            border: 2px solid #000;
            white-space: nowrap; /* Prevents text from wrapping to a second line */
        }

        .status-paid { background-color: #f0f0f0; color: #000; }
        .status-pending { background-color: #fff; border-style: dashed; }

        .internal-info {
            width: 35%;
            font-size: 14px;
            text-align: right;
        }

        .internal-info div { margin-bottom: 4px; }

        /* Footer / Barcode */
        .barcode-area {
            border: 2px dashed #000; 
            padding: 25px; 
            text-align: center; 
            margin-top: auto;
        }

        .courier-note {
            font-size: 13px;
            color: #444;
            text-align: center;
            margin-top: 15px;
            font-style: italic;
        }

        /* Force background colors and layout for printing */
        @media print {
            body { 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
            
            .label-wrapper {
                border: 2px solid #000 !important;
                /* Ensure A5 dimensions are respected */
                width: 148mm;
                height: 210mm;
            }

            .method-bar {
                background-color: #000 !important;
                color: #fff !important;
            }

            .status-badge {
                border: 2px solid #000 !important;
            }
            
            /* Prevent the browser from adding header/footer (date/url) */
            @page {
                margin: 0;
            }
        }

    </style>
</head>
<body>

<div class="label-wrapper">
    <div class="header">
        <h1 class="brand-name">DULYAANA BATHIK</h1>
        <div class="order-id">ORDER ID: #<?= $order['id']; ?></div>
    </div>

    <div class="address-container">
        <div class="address-column">
            <div class="section-title">From:</div>
            <div class="address-text">
                <strong><?php

                echo $companyData['site_name']; ?></strong><br>
                <?php echo $companyData['address']; ?>
            </div>
            <!-- email and contact no -->
            <div class="contact-details">
                <div><strong>Contact No:</strong> <?php echo $companyData['phone01']; ?></div>
                <div><strong>Email:</strong> <?php echo $companyData['email01']; ?></div>
            </div>
        </div>

        <div class="address-column" style="border-left: 1px solid #eee; padding-left: 20px;">
            <div class="section-title">Ship To:</div>
            <div class="address-text">
                <?php
                    // print_r($order);
                ?>
                <span class="ship-to-name"><?= $order['shipping_full_name'] ?></span>
                <?php
                    echo $order['address_line1'] . ",<br>";
                    
                    if($order['address_line2'] != ""){
                        echo $order['address_line2'] . ",<br>";
                    }
                    echo $order['city'] . ",<br>";
                    echo $order['state'] . "<br>";
                    echo $order['postal_code'] . "<br>";
                ?>
                
                <span style="font-size: 16px; margin-top: 8px;"><strong><?= $order['shipping_phone_number'] ?></strong></span>
            </div>
        </div>
    </div>

    <div class="section-title" style="margin-top: 10px;">Payment Details</div>
    <div class="details-row">
        <div class="method-column" style="width: 60%;">
            <div class="method-row-inner">
                <div class="method-bar"><?= $order['payment_method'] ?></div>
                
                <?php
                    $pmtStatus = ($order['payment_status'] == 'completed') ? "Paid" : "Pending";
                    $statusClass = ($pmtStatus == 'Paid') ? "status-paid" : "status-pending";
                ?>

                <div class="status-badge <?= $statusClass ?>">
                    <?= $pmtStatus; ?>
                </div>
            </div>
        </div>
        
        <div class="internal-info">
            <div><strong>Date:</strong> <?= $order['order_date'] ?></div>
            <div><strong>Items:</strong> <?= $order['items_count']; ?> unit(s)</div>
        </div>
    </div>

    <div style="font-size: 16px; margin-top: 15px; color: #333; text-align:center;">
        <strong>Note:</strong> Delivery fees are the responsibility of the customer.
    </div>

    <div class="barcode-area" style="display: none;">
        <div style="font-size: 10px; text-transform: uppercase; margin-bottom: 5px;">Courier Tracking</div>
        <div style="font-size: 35px; letter-spacing: 10px; line-height: 1;">||||||||||||||||||||||</div>
        <div style="font-size: 12px; margin-top: 5px;">[TrackingNumber]</div>
    </div>

    <div class="courier-note">
        Thank you for shopping with <strong>Dulyaana Bathik</strong> - Authentically Sri Lankan
    </div>
</div>

</body>
</html>

<script>
    // window.print when page loads
    window.print();
    window.onafterprint = function() {
        window.close();
    };
</script>