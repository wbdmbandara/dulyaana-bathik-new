<?php
    include_once 'common/header.php';
    setTitle('Sales Report');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('reports', 'sales-report');
?>

    <style>
        .dt-length label{
            margin-left: 5px;
            text-transform: capitalize;
        }

        div.dt-container .dt-search input{
            width: 500px;
        }

        @media (max-width: 768px) {
            div.dt-container .dt-search input {
                width: 100%;
            }
        }
    </style>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Sales Report</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Sales Report</li>
                    </ol>
                </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">            
            <div class="card">
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Sales Report</h5>
                    </div>

                    <!-- Filter Report -->
                    <form action="" method="GET">
                        <div class="row mx-auto g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="start_date" value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>">
                                    <span class="input-group-text">to</span>
                                    <input type="date" class="form-control" name="end_date" value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Order Statuses</option>
                                    <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'selected' : '' ?>>Processing</option>
                                    <option value="shipped" <?= (isset($_GET['status']) && $_GET['status'] == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <select class="form-select" name="payment_status">
                                    <option value="">All Payment Statuses</option>
                                    <option value="pending" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="completed" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                    <option value="failed" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'failed') ? 'selected' : '' ?>>Failed</option>
                                    <option value="refunded" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'refunded') ? 'selected' : '' ?>>Refunded</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <select class="form-select" name="payment_method">
                                    <option value="">All Payment Methods</option>
                                    <option value="Bank Transfer" <?= (isset($_GET['payment_method']) && $_GET['payment_method'] == 'Bank Transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="Cash on Delivery" <?= (isset($_GET['payment_method']) && $_GET['payment_method'] == 'Cash on Delivery') ? 'selected' : '' ?>>Cash on Delivery</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex gap-2 justify-content-center justify-content-md-end">
                                <button class="btn btn-primary" type="submit">Filter</button>
                                <button type="button" class="btn btn-danger" onclick="location.href='?'">Reset</button>
                            </div>
                        </div>
                    </form>

                    <!-- Report table -->
                    <div class="table-responsive">
                        <table id="salesReportTable" class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Total Value</th>
                                    <th scope="col">Payment Details</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totalValue = 0;
                                    foreach ($orders as $index => $order): ?>
                                        <?php
                                            $totalValue += $order['final_amount'];
                                            // Payment Status
                                            $pmtStatus = strtolower($order['payment_status']);
                                            $pmtBgColor = 'transparent';
                                            $pmtIcon = '';
                                            switch ($pmtStatus) {
                                                case 'pending':
                                                    $pmtBgColor = '#fff3cd'; // Light yellow
                                                    $pmtIcon = '<i class="me-2 bi bi-clock"></i>';
                                                    break;
                                                case 'completed':
                                                    $pmtBgColor = '#d1e7dd'; // Light green
                                                    $pmtIcon = '<i class="me-2 bi bi-check-circle"></i>';
                                                    break;
                                                case 'failed':
                                                    $pmtBgColor = '#f8d7da'; // Light red
                                                    $pmtIcon = '<i class="me-2 bi bi-x-circle"></i>';
                                                    break;
                                                case 'refunded':
                                                    $pmtBgColor = '#cce5ff'; // Light blue
                                                    $pmtIcon = '<i class="me-2 bi bi-arrow-counterclockwise"></i>';
                                                    break;
                                                default:
                                                    $pmtBgColor = 'transparent';
                                            }

                                            // Payment Method Abbreviation
                                            $paymentMethod = htmlspecialchars($order['payment_method']);
                                            if($paymentMethod == 'Bank Transfer') {
                                                $paymentMethod = 'Bank Trans.';
                                            }else if($paymentMethod == 'Cash on Delivery') {
                                                $paymentMethod = 'COD';
                                            }else {
                                                $paymentMethod = 'N/A';
                                            }

                                            // Order Status BG
                                            $orderStatus = htmlspecialchars($order['status']);
                                            $orderStatusBg = '';
                                            $orderStatusTxtColor = '';
                                            switch (strtolower($orderStatus)) {
                                                case 'pending':
                                                    $orderStatusBg = '#fff3cd';
                                                    // $orderStatusTxtColor = '#856404';
                                                    break;
                                                case 'completed':
                                                    $orderStatusBg = '#d1e7dd';
                                                    $orderStatusTxtColor = '#0f5132';
                                                    break;
                                                case 'cancelled':
                                                    $orderStatusBg = '#f8d7da';
                                                    $orderStatusTxtColor = '#721c24';
                                                    break;
                                                case 'processing':
                                                    $orderStatusBg = '#cce5ff';
                                                    $orderStatusTxtColor = '#000000';
                                                    break;
                                                case 'shipped':
                                                    $orderStatusBg = '#0d6efd';
                                                    $orderStatusTxtColor = '#ffffff';
                                                    break;
                                                default:
                                                    $orderStatusBg = '#6c757d';
                                                    $orderStatusTxtColor = '#000000';
                                            }
                                        ?>
                                        <tr>
                                            <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                            <td class="text-center"><?= htmlspecialchars($order['id']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($order['order_date']) ?></td>
                                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                            <td class="text-center"><?= number_format($order['final_amount'], 2) ?></td>
                                            <td class="text-center" style="background-color: <?= $pmtBgColor ?>;">
                                                <?= $pmtIcon ?><?= $paymentMethod ?>
                                            </td>
                                            <td class="text-center text-capitalize" style="background-color: <?= $orderStatusBg ?>; color: <?= $orderStatusTxtColor ?>;">
                                                <?= htmlspecialchars($order['status']) ?>
                                            </td>
                                            <td class="text-center gap-2">
                                                <button class="btn btn-sm btn-primary" onclick="viewOrder(<?= $order['id'] ?>)"><i class="bi bi-eye"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <th colspan="4" class="text-end">Total Value:</th>
                                    <th class="text-center"><?= number_format($totalValue, 2) ?></th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- End report table -->
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- view Order Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewOrderModalLabel">
                        <i class="bi bi-receipt"></i> Order Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailsContent">
                        <!-- Order details will be loaded here via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading order details...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Close
                    </button>
                    <a href="#" class="btn btn-primary" target="_blank">
                        <i class="bi bi-printer"></i> Print
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- View Payment Slip Modal -->
    <div class="modal fade" id="viewPaymentSlipModal" tabindex="-1" aria-labelledby="viewPaymentSlipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="viewPaymentSlipModalLabel">
                        <i class="bi bi-file-earmark-image"></i> Payment Slip
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="paymentSlipContent">
                        <!-- Payment slip will be loaded here via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading payment slip...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div id="paymentStatusDisplay"></div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                        <button type="button" class="btn btn-primary" id="downloadPaymentSlipBtn">
                            <i class="bi bi-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    include_once 'common/footer.php';
?>

    <script>
        function viewOrder(orderId) {
            const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
            const modalContent = document.getElementById('orderDetailsContent');
            const modalTitle = document.getElementById('viewOrderModalLabel');
            const modalFooter = document.querySelector('#viewOrderModal .modal-footer');
            
            // Update modal title
            modalTitle.innerHTML = '<i class="bi bi-receipt"></i> Order Details';

            // Reset modal footer
            modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
                <a href="<?= url('/generate-pdf') ?>/`+ orderId +`" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print
                </a>
            `;
            
            // Show loading state
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading order details...</p>
                </div>
            `;
            
            modal.show();
            
            // Fetch order details via AJAX
            fetch(`<?= url('/orders/view') ?>/${orderId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const order = data.order_data;
                    const items = data.ordered_items || [];
                    
                    modalContent.innerHTML = `
                        <div class="container-fluid">
                            <!-- Order Header -->
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <h6 class="text-muted mb-2">Order Information</h6>
                                    <p class="mb-1"><strong>Order ID:</strong> #${order.id}</p>
                                    <p class="mb-1"><strong>Order Date:</strong> ${order.order_date} ${order.order_time || ''}</p>
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge text-capitalize bg-${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                                    ${order.courier_name ? `<p class="mb-1"><strong>Courier:</strong> ${order.courier_name}</p>` : ''}
                                    ${order.courier_tracking_no ? `<p class="mb-1"><strong>Courier Tracking Number:</strong> ${order.courier_tracking_no}</p>` : ''}
                                </div>
                                <div class="col-12 col-md-6">
                                    <h6 class="text-muted mb-2">Customer Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> ${order.customer_name}</p>
                                    <p class="mb-1"><strong>Email:</strong> ${order.email}</p>
                                    <p class="mb-1"><strong>Phone:</strong> ${order.phone}</p>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            ${order.address_line1 ? `
                            <div class="row mb-3 border-top pt-2">
                                <div class="col-12 col-md-6">
                                <h6 class="text-muted mb-2">Shipping Details</h6>
                                <p class="mb-1"><strong>Full Name:</strong> ${order.shipping_full_name}</p>
                                <p class="mb-1"><strong>Phone Number:</strong> ${order.shipping_phone_number}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <p class="mb-0"><strong>Shipping Address:</strong> ${order.address_line1}${order.address_line2 ? ', <br>' + order.address_line2 : ''}, <br> ${order.city}, ${order.state} <br> ${order.postal_code}</p>
                                </div>
                            </div>
                            ` : ''}

                            <!-- Order Items -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Order Items (${order.items_count} item(s), ${order.total_quantity} qty)</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-end">Unit Price</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${items.map((item, index) => `
                                                    <tr>
                                                        <td>${index + 1}</td>
                                                        <td>${item.name}</td>
                                                        <td class="text-center">${item.quantity}</td>
                                                        <td class="text-end">${currencyFormat(item.price)}</td>
                                                        <td class="text-end">${currencyFormat(item.value)}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    ${order.note ? `
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-muted mb-2">Notes</h6>
                                            <p class="text-muted">${order.note}</p>
                                        </div>
                                    </div>
                                    ` : ''}

                                    ${order.admin_note ? `
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-muted mb-2">Admin Notes</h6>
                                            <p class="text-muted">${order.admin_note}</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
                                <div class="col-12 col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td class="text-end">${currencyFormat(order.total_amount)}</td>
                                        </tr>
                                        ${parseFloat(order.discount) > 0 ? `
                                        <tr>
                                            <td><strong>Discount:</strong></td>
                                            <td class="text-end text-danger">- ${currencyFormat(order.discount)}</td>
                                        </tr>
                                        ` : ''}
                                        <tr class="table-light">
                                            <td><strong>Total Amount:</strong></td>
                                            <td class="text-end"><strong>${currencyFormat(order.final_amount)}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Method:</strong></td>
                                            <td class="text-end">${order.payment_method}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Status:</strong></td>
                                            <td class="text-end text-capitalize">${order.payment_status}</td>
                                        </tr>
                                        ${order.payment_method.toLowerCase() === 'bank transfer' && order.payment_slip ? `
                                            <tr>
                                                <td><strong>Payment Slip:</strong></td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-secondary" onclick="viewPaymentSlip('${order.payment_slip}', '${order.payment_status}')">View Payment Slip</button>
                                                </td>
                                            </tr>
                                        ` : ''}
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message || 'Failed to load order details.'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> An error occurred while loading order details.
                    </div>
                `;
            });
        }

        function getStatusBadgeClass(status) {
            const statusClasses = {
                'pending': 'warning',
                'processing': 'info',
                'completed': 'success',
                'cancelled': 'danger',
                'shipped': 'primary'
            };
            return statusClasses[status.toLowerCase()] || 'secondary';
        }

        function viewPaymentSlip(paymentSlip, paymentStatus) {
            // Open the payment slip modal
            const modal = new bootstrap.Modal(document.getElementById('viewPaymentSlipModal'));
            const modalContent = document.getElementById('paymentSlipContent');

            if (!paymentSlip) {
                modalContent.innerHTML = `
                <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle"></i> Payment slip not found.
                </div>
                `;
            } else {
                // Load payment slip into modal if image
                if (paymentSlip.endsWith('.jpg') || paymentSlip.endsWith('.png') || paymentSlip.endsWith('.jpeg')) {
                    modalContent.innerHTML = `
                        <div class="text-center">
                            <img src="/payment_slips/${paymentSlip}" alt="Payment Slip" class="img-fluid" style="max-height: 500px;">
                        </div>
                    `;
                }
                else if (paymentSlip.endsWith('.pdf')) {
                    modalContent.innerHTML = `
                        <div class="text-center">
                            <iframe src="/payment_slips/${paymentSlip}" alt="Payment Slip" class="img-fluid" style="width: 100%; height: 500px;"></iframe>
                        </div>
                    `;
                }
                else {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Invalid payment slip format.
                        </div>
                    `;
                }
            }

            // Update payment status display
            const paymentStatusDisplay = document.getElementById('paymentStatusDisplay');
            paymentStatusDisplay.innerHTML = `<strong>Payment Status:</strong> <span class="text-capitalize">${paymentStatus}</span>`;

            modal.show();
        }

        // Download Payment Slip
        const downloadPaymentSlipBtn = document.getElementById('downloadPaymentSlipBtn');
        downloadPaymentSlipBtn.addEventListener('click', function() {
            const paymentSlip = document.getElementById('paymentSlipContent').querySelector('img, iframe').src;
            const link = document.createElement('a');
            link.href = paymentSlip;
            link.download = paymentSlip.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // datatable
        $(document).ready(function() {
            $('#salesReportTable').DataTable({
                "responsive": true,
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [6, 7] }
                ],
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10,
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search orders..."
                }
            });
        });
    </script>