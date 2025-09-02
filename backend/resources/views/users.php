<?php
    include_once 'common/header.php';
    setTitle('Users');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('users', '');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Users</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                        <h5 class="card-title mb-0">Manage Users</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newUserModal">Add New User</button>
                    </div>

                    <!-- users table -->
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Mobile No</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($users as $index => $user): ?>
                                    <tr>
                                        <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td class="text-capitalize"><?= htmlspecialchars($user['role']) ?></td>
                                        <td><?= htmlspecialchars($user['mobileno']) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning editbtn" onclick="editUser(<?= $user['id'] ?>)"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteUser(<?= $user['id'] . ',\'' . $user['name'] . '\'' ?>)"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End users table -->

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal for adding new user -->
    <div class="modal fade" id="newUserModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <form id="newUserForm" method="post" action="/users/new">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    
                    <div class="modal-body">
                        <!-- Validation Errors -->
                        <div id="validationErrors" class="alert alert-danger" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" id="userId" name="id">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="userName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobileno" class="form-label">Mobile No</label>
                            <input type="text" class="form-control" id="mobileno" name="mobileno" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" name="password" required>
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

<?php
    include_once 'common/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const newUserForm = document.getElementById('newUserForm');
        const validationErrors = document.getElementById('validationErrors');
        const errorList = document.getElementById('errorList');
        const userId = document.getElementById('userId');

        newUserForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Clear previous errors
            validationErrors.style.display = 'none';
            errorList.innerHTML = '';

            // Get form data
            const formData = new FormData(newUserForm);

            let url = '/users/new';
            if (userId.value) {
                url = `/users/update/${userId.value}`;
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newUserModal'));
                    modal.hide();
                    newUserForm.reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'User saved successfully',
                        timer: 6000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 6000);
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

    document.getElementById('newUserModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('newUserForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('validationErrors').style.display = 'none';
        document.getElementById('errorList').innerHTML = '';
        document.querySelector('#newUserModal .modal-title').textContent = 'Add New User';
        document.getElementById('userPassword').required = true;
    });

    function editUser(userId) {
        try {
            const modal = new bootstrap.Modal(document.getElementById('newUserModal'));
            const modalTitle = document.querySelector('#newUserModal .modal-title');
            modalTitle.textContent = 'Edit User';
            document.getElementById('userPassword').required = false;
            modal.show();

            // Fetch user data via AJAX and populate the modal fields
            fetch(`/users/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                const user = data.user;
                document.getElementById('userId').value = user.id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userRole').value = user.role;
                document.getElementById('mobileno').value = user.mobileno;
                } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch user data'
                });
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });

        } catch (err) {
            console.error('Unexpected error in editUser:', err);
        }
    }

    function deleteUser(userId, userName) {
        Swal.fire({
            title: `Are you sure you want to delete ${userName}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete user
                fetch(`/users/delete/${userId}`, {
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
                            text: data.message || 'User has been deleted.',
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
                            text: data.message || 'Failed to delete user.'
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