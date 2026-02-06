<?php
    include_once 'common/header.php';
    setTitle('Bank Details');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('payments', 'bank-details');
?>

<style>
    .label-border {
        border-bottom: 1px solid #000;
        padding-bottom: 3px;
    }
</style>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Bank Details</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Bank Details</li>
                    </ol>
                </nav>
        </div><!-- End Page Title -->

        <div class="card">
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible mt-3 fade show" role="alert">
                            <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible mt-3 fade show" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Manage Bank Details</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">Add Bank Details</button>
                    </div>

                    <section class="section dashboard">

                        <!-- Bank Details Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bank Name</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Branch</th>
                                        <th>Branch Code</th>
                                        <th>Instructions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($bankDetails) && count($bankDetails) > 0): ?>
                                        <?php foreach($bankDetails as $index => $bank): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($bank->bank_name ?? '') ?></td>
                                                <td><?= htmlspecialchars($bank->account_name ?? '') ?></td>
                                                <td><?= htmlspecialchars($bank->account_number ?? '') ?></td>
                                                <td><?= htmlspecialchars($bank->branch ?? '') ?></td>
                                                <td><?= htmlspecialchars($bank->branch_code ?? '') ?></td>
                                                <td><?= htmlspecialchars($bank->instructions ?? '') ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" onclick="editBankDetails(<?= htmlspecialchars(json_encode($bank), ENT_QUOTES, 'UTF-8') ?>)" data-bs-target="#addBankModal">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteBankDetails(<?= htmlspecialchars(json_encode($bank), ENT_QUOTES, 'UTF-8') ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No bank details found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Bank Details Modal -->
                        <div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addBankModalLabel">Add Bank Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?= url('bank-details/store') ?>" method="POST" id="bankDetailsForm">
                                        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="bank_name" class="form-label">Bank Name</label>
                                                <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="account_name" class="form-label">Account Name</label>
                                                <input type="text" class="form-control" id="account_name" name="account_name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="account_number" class="form-label">Account Number</label>
                                                <input type="text" class="form-control" id="account_number" name="account_number" required>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="branch" class="form-label">Branch</label>
                                                    <input type="text" class="form-control" id="branch" name="branch" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="branch_code" class="form-label">Branch Code</label>
                                                    <input type="text" class="form-control" id="branch_code" name="branch_code" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="instructions" class="form-label">Instructions</label>
                                                <textarea class="form-control" id="instructions" name="instructions" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="saveBankDetailsBtn">Save Bank Details</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </section>

                </div>
            </div>

    </main><!-- End #main -->

<?php
    include_once 'common/footer.php';
?>

    <script>
        function editBankDetails(bank) {
            document.getElementById('bankDetailsForm').setAttribute('action', '/bank-details/update/' + bank.id);
            document.getElementById('addBankModalLabel').innerText = 'Edit Bank Details';
            document.getElementById('bank_name').value = bank.bank_name || '';
            document.getElementById('account_name').value = bank.account_name || '';
            document.getElementById('account_number').value = bank.account_number || '';
            document.getElementById('branch').value = bank.branch || '';
            document.getElementById('branch_code').value = bank.branch_code || '';
            document.getElementById('instructions').value = bank.instructions || '';
            document.getElementById('saveBankDetailsBtn').innerText = 'Update Bank Details';
        }

        // Reset modal on close
        var addBankModal = document.getElementById('addBankModal');
        addBankModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('bankDetailsForm').setAttribute('action', '/bank-details/store');
            document.getElementById('addBankModalLabel').innerText = 'Add Bank Details';
            document.getElementById('bank_name').value = '';
            document.getElementById('account_name').value = '';
            document.getElementById('account_number').value = '';
            document.getElementById('branch').value = '';
            document.getElementById('branch_code').value = '';
            document.getElementById('instructions').value = '';
            document.getElementById('saveBankDetailsBtn').innerText = 'Save Bank Details';
        });

        function deleteBankDetails(bank) {
            const bankId = bank.id;
            const bankName = bank.bank_name;
            const accountNumber = bank.account_number;
            const branch = bank.branch;
            Swal.fire({
                title: 'Are you sure you want to delete this bank detail?',
                html: `<p><strong>Bank Name:</strong> ${bankName}</p>
                       <p><strong>Account Number:</strong> ${accountNumber}</p>
                       <p><strong>Branch:</strong> ${branch}</p>
                       <p class="text-danger">This action cannot be undone.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete bank detail
                    fetch(`/bank-details/delete/${bankId}`, {
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
                                text: data.message || 'Bank detail has been deleted.',
                                timer: 6000,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 6000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to delete bank detail.'
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