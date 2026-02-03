<?php
    include_once 'common/header.php';
    setTitle('Orders');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('orders', 'orders');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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
                        <h5 class="card-title mb-0">Manage Orders</h5>
                    </div>

                    <!-- Search Orders -->
                    <form action="" method="GET" class="mt-3">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="search" placeholder="Search by Customer Name, Phone No, Email, Order Date, Payment Method or Order Status" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="hide_zero_qty" id="hideZeroQty" value="1" <?= isset($_GET['hide_zero_qty']) && $_GET['hide_zero_qty'] == '1' ? 'checked' : '' ?>>
                                <label class="form-check-label ms-1" for="hideZeroQty">Hide zero qty</label>
                            </div>
                            <button class="btn btn-primary" type="submit">Search</button>
                            <button type="button" class="btn btn-danger" onclick="location.href='?search='">Reset</button>
                        </div>
                    </form>

                    <!-- Orders table -->
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Customer</th>
                                <th scope="col" class="d-none">Email</th>
                                <th scope="col">Contact No</th>
                                <th scope="col">Total Value</th>
                                <th scope="col">Payment Details</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($orders as $index => $order): ?>
                                    <?php
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
                                        <td style="max-width: 200px; word-wrap: break-word; white-space: normal; display:none;"><?= htmlspecialchars($order['email']) ?></td>
                                        <td><?= htmlspecialchars($order['phone']) ?></td>
                                        <td class="text-center"><?= number_format($order['final_amount'], 2) ?></td>
                                        <td class="text-center" style="background-color: <?= $pmtBgColor ?>;">
                                            <?= $pmtIcon ?><?= $paymentMethod ?>
                                        </td>
                                        <td class="text-center text-capitalize" style="background-color: <?= $orderStatusBg ?>; color: <?= $orderStatusTxtColor ?>;">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </td>
                                        <td class="text-center gap-2">
                                            <button class="btn btn-sm btn-primary" onclick="viewOrder(<?= $order['id'] ?>)"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-warning" onclick="editOrder(<?= $order['id'] ?>)"><i class="bi bi-pencil"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End orders table -->

                    <?= $orders->appends(request()->query())->links('custom-pagination') ?>
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
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print
                    </button>
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
            fetch(`/orders/view/${orderId}`, {
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
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Order Information</h6>
                                    <p class="mb-1"><strong>Order ID:</strong> #${order.id}</p>
                                    <p class="mb-1"><strong>Order Date:</strong> ${order.order_date} ${order.order_time || ''}</p>
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge text-capitalize bg-${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                                    ${order.courier_name ? `<p class="mb-1"><strong>Courier:</strong> ${order.courier_name}</p>` : ''}
                                    ${order.courier_tracking_no ? `<p class="mb-1"><strong>Courier Tracking Number:</strong> ${order.courier_tracking_no}</p>` : ''}
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Customer Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> ${order.customer_name}</p>
                                    <p class="mb-1"><strong>Email:</strong> ${order.email}</p>
                                    <p class="mb-1"><strong>Phone:</strong> ${order.phone}</p>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            ${order.address_line1 ? `
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-muted mb-2">Shipping Address</h6>
                                    <p class="mb-0">${order.address_line1}${order.address_line2 ? ', ' + order.address_line2 : ''}, ${order.city}, ${order.state} ${order.postal_code}</p>
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
                                <div class="col-md-6">
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
                                <div class="col-md-6">
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

        function editOrder(orderId) {
            const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
            const modalContent = document.getElementById('orderDetailsContent');
            const modalTitle = document.getElementById('viewOrderModalLabel');
            const modalFooter = document.querySelector('#viewOrderModal .modal-footer');
            
            // Update modal title
            modalTitle.innerHTML = '<i class="bi bi-pencil-square"></i> Edit Order';
            
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
            fetch(`/orders/view/${orderId}`, {
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
                    <form id="editOrderForm">
                    <input type="hidden" name="order_id" value="${order.id}">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    
                    <!-- Order Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                        <h6 class="text-muted mb-2">Order Information</h6>
                        <p class="mb-1"><strong>Order ID:</strong> #${order.id}</p>
                        <p class="mb-1"><strong>Order Date:</strong> ${order.order_date} ${order.order_time || ''}</p>
                        </div>
                        <div class="col-md-6">
                        <h6 class="text-muted mb-2">Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> ${order.customer_name}</p>
                        <p class="mb-1"><strong>Email:</strong> ${order.email}</p>
                        <p class="mb-1"><strong>Phone:</strong> ${order.phone}</p>
                        </div>
                    </div>

                    <!-- Editable Fields -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                        <div class="mb-3">
                            <label for="orderStatus" class="form-label"><strong>Order Status</strong></label>
                            <select class="form-select" id="orderStatus" name="status" required>
                            <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                            <option value="shipped" ${order.status === 'shipped' ? 'selected' : ''}>Shipped</option>
                            <option value="completed" ${order.status === 'completed' ? 'selected' : ''}>Completed</option>
                            <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="mb-3">
                            <label for="courierName" class="form-label"><strong>Courier Name</strong></label>
                            <input type="text" class="form-control" id="courierName" name="courier_name" 
                               value="${order.courier_name || ''}" placeholder="Enter courier name">
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="mb-3">
                            <label for="trackingNumber" class="form-label"><strong>Courier Tracking Number</strong></label>
                            <input type="text" class="form-control" id="trackingNumber" name="tracking_number" 
                               value="${order.courier_tracking_no || ''}" placeholder="Enter Courier tracking number">
                        </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    ${order.address_line1 ? `
                    <div class="row mb-4">
                        <div class="col-12">
                        <h6 class="text-muted mb-2">Shipping Address</h6>
                        <p class="mb-0">${order.address_line1}${order.address_line2 ? ', ' + order.address_line2 : ''}, ${order.city}, ${order.state} ${order.postal_code}</p>
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
                                    <td class="text-end">Rs. ${parseFloat(item.price).toFixed(2)}</td>
                                    <td class="text-end">Rs. ${parseFloat(item.value).toFixed(2)}</td>
                                </tr>
                                `).join('')}
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                        <table class="table table-sm">
                            <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">Rs. ${parseFloat(order.total_amount).toFixed(2)}</td>
                            </tr>
                            ${parseFloat(order.discount) > 0 ? `
                            <tr>
                            <td><strong>Discount:</strong></td>
                            <td class="text-end text-danger">- Rs. ${parseFloat(order.discount).toFixed(2)}</td>
                            </tr>
                            ` : ''}
                            <tr class="table-light">
                            <td><strong>Total Amount:</strong></td>
                            <td class="text-end"><strong>Rs. ${parseFloat(order.final_amount).toFixed(2)}</strong></td>
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

                    <!-- Notes -->
                    <div class="row mt-3">
                        <div class="col-8">
                        <div class="mb-3">
                            <label for="orderNotes" class="form-label"><strong>Notes</strong></label>
                            <textarea class="form-control" id="orderNotes" name="notes" rows="3" placeholder="Add notes about this order">${order.admin_note || ''}</textarea>
                        </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="paymentStatus" class="form-label"><strong>Payment Status</strong></label>
                                <select class="form-select" id="paymentStatus" name="payment_status">
                                    <option value="pending" ${order.payment_status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="completed" ${order.payment_status === 'completed' ? 'selected' : ''}>Completed</option>
                                    <option value="failed" ${order.payment_status === 'failed' ? 'selected' : ''}>Failed</option>
                                    <option value="refunded" ${order.payment_status === 'refunded' ? 'selected' : ''}>Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                `;

                // Update modal footer with save button
                modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="saveOrderChanges()">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
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

        function saveOrderChanges() {
            const form = document.getElementById('editOrderForm');
            const formData = new FormData(form);
            const orderId = formData.get('order_id');
            console.log(formData);
            
            // Show loading state
            const saveButton = event.target;
            const originalText = saveButton.innerHTML;
            saveButton.disabled = true;
            saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            
            // Send update request
            fetch(`/orders/update/${orderId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
            })
            .then(response => response.json())
            .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('viewOrderModal'));
                modal.hide();
                
                // Show success message and reload page
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Order updated successfully!',
                    timer: 5000,
                    showConfirmButton: false
                });
                setTimeout(() => {
                    location.reload();
                }, 5000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to update order.'
                });
                saveButton.disabled = false;
                saveButton.innerHTML = originalText;
            }
            })
            .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while updating the order.'
            });
            saveButton.disabled = false;
            saveButton.innerHTML = originalText;
            });
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
    </script>