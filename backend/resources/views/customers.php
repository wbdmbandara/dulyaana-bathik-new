<?php
    include_once 'common/header.php';
    setTitle('Customers');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('customers', '');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Customers</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
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
                        <h5 class="card-title mb-0">Manage Customers</h5>
                    </div>

                    <!-- customers table -->
                    <table class="table table-striped table-bordered mt-3" id="customerTable">
                        <thead>
                            <tr class="text-center">
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col" class="text-center">Name</th>
                                <th scope="col" class="text-center">Email</th>
                                <th scope="col" class="text-center">Mobile No</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($customers as $index => $customer): ?>
                                    <tr>
                                        <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                        <td><?= htmlspecialchars($customer['name']) ?></td>
                                        <td><?= htmlspecialchars($customer['email']) ?></td>
                                        <td class="text-capitalize"><?= htmlspecialchars($customer['phone']) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info viewbtn" onclick="viewCustomer(<?= $customer['id'] ?>)"><i class="bi bi-eye"></i> View</button>
                                            <!-- <button class="btn btn-sm btn-warning editbtn" onclick="editCustomer(<?= $customer['id'] ?>)"><i class="bi bi-pencil"></i> Edit</button> -->
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteCustomer(<?= $customer['id'] . ',\'' . $customer['name'] . '\'' ?>)"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End customers table -->

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal for adding new customer -->
    <div class="modal fade" id="newCustomerModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <form id="newCustomerForm" method="post" action="<?= url('/customers/new'); ?>">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    
                    <div class="modal-body">
                        <!-- Validation Errors -->
                        <div id="validationErrors" class="alert alert-danger" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" id="customerId" name="id">
                            <label for="customerName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="customerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobileno" class="form-label">Mobile No</label>
                            <input type="text" class="form-control" id="mobileno" name="mobileno" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View customer modal -->
    <div class="modal fade" id="viewCustomerModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4 font-weight-bold">Name:</div>
                        <div class="col-8" id="viewCustomerName"></div>
                        <div class="col-4 font-weight-bold">Email:</div>
                        <div class="col-8" id="viewCustomerEmail"></div>
                        <div class="col-4 font-weight-bold">Mobile No:</div>
                        <div class="col-8" id="viewCustomerMobileNo"></div>
                        <div class="col-4 font-weight-bold">Gender:</div>
                        <div class="col-8" id="viewCustomerGender"></div>
                        <div class="col-4 font-weight-bold">Birthday:</div>
                        <div class="col-8" id="viewCustomerBirthday"></div>
                        <div class="col-4 font-weight-bold">Newsletter Subscription:</div>
                        <div class="col-8" id="viewCustomerNewsletter"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php
    include_once 'common/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const customerTable = $('#customerTable').DataTable();

        const newCustomerForm = document.getElementById('newCustomerForm');
        const validationErrors = document.getElementById('validationErrors');
        const errorList = document.getElementById('errorList');
        const customerId = document.getElementById('customerId');

        newCustomerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Clear previous errors
            validationErrors.style.display = 'none';
            errorList.innerHTML = '';

            // Get form data
            const formData = new FormData(newCustomerForm);

            let url = '/customers/new';
            if (customerId.value) {
                url = `/customers/update/${customerId.value}`;
            }
            // Send AJAX request
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success - close modal and show success message
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newCustomerModal'));
                    modal.hide();
                    newCustomerForm.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Customer saved successfully',
                        timer: 5000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                } else {
                    // Show error message
                    if (data.errors) {
                        // Validation errors
                        Object.values(data.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                const li = document.createElement('li');
                                li.textContent = error;
                                errorList.appendChild(li);
                            });
                        });
                        validationErrors.style.display = 'block';
                    } else {
                        // General error
                        const li = document.createElement('li');
                        li.textContent = data.message || 'An error occurred';
                        errorList.appendChild(li);
                        validationErrors.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const li = document.createElement('li');
                li.textContent = 'An unexpected error occurred';
                errorList.appendChild(li);
                validationErrors.style.display = 'block';
            });
        });
    });

    document.getElementById('newCustomerModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('newCustomerForm').reset();
        document.getElementById('customerId').value = '';
        document.getElementById('validationErrors').style.display = 'none';
        document.getElementById('errorList').innerHTML = '';
        document.querySelector('#newCustomerModal .modal-title').textContent = 'Add New Customer';
        document.getElementById('customerPassword').required = true;
    });

    function viewCustomer(customerId) {
        try {
            fetch(`/customers/${customerId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const customer = data.customer;
                    document.getElementById('viewCustomerName').textContent = customer.name;
                    if(customer.email_confirmed) {
                        document.getElementById('viewCustomerEmail').innerHTML = ' <i class="bi bi-check-circle-fill text-success"></i> <a href="mailto:' + customer.email + '">' + customer.email + '</a>';
                    } else {
                        document.getElementById('viewCustomerEmail').innerHTML = ' <i class="bi bi-x-circle-fill text-danger"></i> <a href="mailto:' + customer.email + '">' + customer.email + '</a>';
                    }
                    if(customer.phone && customer.phone_confirmed) {
                        document.getElementById('viewCustomerMobileNo').innerHTML = ' <i class="bi bi-check-circle-fill text-success"></i> ' + customer.phone;
                    } else if(customer.phone && !customer.phone_confirmed) {
                        document.getElementById('viewCustomerMobileNo').innerHTML = ' <i class="bi bi-x-circle-fill text-danger"></i> ' + customer.phone;
                    }else {
                        document.getElementById('viewCustomerMobileNo').innerHTML = '<span class="text-muted">N/A</span>';
                    }
                    document.getElementById('viewCustomerGender').textContent = customer.gender ? customer.gender : 'N/A';
                    document.getElementById('viewCustomerBirthday').textContent = customer.birthday ? new Date(customer.birthday).toLocaleDateString() : 'N/A';
                    document.getElementById('viewCustomerNewsletter').innerHTML = customer.subscribed_to_newsletter ? '<i class="bi bi-check-circle-fill text-success"></i> Subscribed' : '<i class="bi bi-x-circle-fill text-danger"></i> Not Subscribed';
                    const modal = new bootstrap.Modal(document.getElementById('viewCustomerModal'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch customer data'
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching customer data:', error);
            });
        } catch (err) {
            console.error('Unexpected error in viewCustomer:', err);
        }
    }

    function editCustomer(customerId) {
        try {
            const modal = new bootstrap.Modal(document.getElementById('newCustomerModal'));
            const modalTitle = document.querySelector('#newCustomerModal .modal-title');
            modalTitle.textContent = 'Edit Customer';
            document.getElementById('customerPassword').required = false;
            modal.show();

            // Fetch customer data via AJAX and populate the modal fields
            fetch(`/customers/${customerId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                const customer = data.customer;
                document.getElementById('customerId').value = customer.id;
                document.getElementById('customerName').value = customer.name;
                document.getElementById('customerEmail').value = customer.email;
                document.getElementById('customerRole').value = customer.role;
                document.getElementById('mobileno').value = customer.mobileno;
                } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch customer data'
                });
                }
            })
            .catch(error => {
                console.error('Error fetching customer data:', error);
            });

        } catch (err) {
            console.error('Unexpected error in editCustomer:', err);
        }
    }

    function deleteCustomer(customerId, customerName) {
        Swal.fire({
            title: `Are you sure you want to delete ${customerName}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/customers/delete/${customerId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'Customer has been deleted.',
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
                            text: data.message || 'Failed to delete customer.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.'
                    });
                });
            }
        });
    }
</script>