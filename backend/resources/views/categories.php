<?php
    include_once 'common/header.php';
    setTitle('Categories');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('sarees', 'categories');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Categories</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Categories</li>
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
                        <h5 class="card-title mb-0">Manage Categories</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">Add New Category</button>
                    </div>

                    <!-- categories table -->
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Parent Category</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($categories as $index => $category): ?>
                                    <tr>
                                        <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                        <td><?= htmlspecialchars($category['cat_name']) ?></td>
                                        <td><?= htmlspecialchars($category['cat_description']) ?></td>
                                        <td><?php if($category['parent_name']){echo htmlspecialchars($category['parent_name']); }else{echo 'None';} ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning editbtn" onclick="editCategory(<?= $category['id'] ?>)"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteCategory(<?= $category['id'] . ',\'' . $category['cat_name'] . '\'' ?>)"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End categories table -->

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal for adding new category -->
    <div class="modal fade" id="newCategoryModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <form id="newCategoryForm" method="post" action="/categories/new">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    
                    <div class="modal-body">
                        <!-- Validation Errors -->
                        <div id="validationErrors" class="alert alert-danger" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" id="categoryId" name="id">
                            <label for="categoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="categoryName" name="cat_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="categoryDescription" name="cat_description" required>
                        </div>
                        <div class="mb-3">
                            <label for="parentCategory" class="form-label">Parent Category</label>
                            <select class="form-select" id="parentCategory" name="parent_id" required>
                                <option value="" disabled selected>Select Parent Category</option>
                                <option value="0">None</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['cat_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="categorySlug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="categorySlug" name="cat_slug" required>
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
        const newCategoryForm = document.getElementById('newCategoryForm');
        const validationErrors = document.getElementById('validationErrors');
        const errorList = document.getElementById('errorList');
        const categoryId = document.getElementById('categoryId');

        newCategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Clear previous errors
            validationErrors.style.display = 'none';
            errorList.innerHTML = '';

            // Get form data
            const formData = new FormData(newCategoryForm);

            let url = '/categories/new';
            if (categoryId.value) {
                url = `/categories/update/${categoryId.value}`;
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newCategoryModal'));
                    modal.hide();
                    newCategoryForm.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Category saved successfully',
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

    document.getElementById('newCategoryModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('newCategoryForm').reset();
        document.getElementById('categoryId').value = '';
        document.getElementById('validationErrors').style.display = 'none';
        document.getElementById('errorList').innerHTML = '';
        document.querySelector('#newCategoryModal .modal-title').textContent = 'Add New Category';
    });

    function editCategory(categoryId) {
        try {
            const modal = new bootstrap.Modal(document.getElementById('newCategoryModal'));
            const modalTitle = document.querySelector('#newCategoryModal .modal-title');
            modalTitle.textContent = 'Edit Category';
            document.getElementById('categoryId').value = categoryId;
            modal.show();

            // Fetch category data via AJAX and populate the modal fields
            fetch(`/categories/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                const category = data.category;
                document.getElementById('categoryName').value = category.cat_name;
                document.getElementById('categoryDescription').value = category.cat_description;
                document.getElementById('categorySlug').value = category.cat_slug;
                document.getElementById('parentCategory').value = category.parent_id;
                } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch category data'
                });
                }
            })
            .catch(error => {
                console.error('Error fetching category data:', error);
            });

        } catch (err) {
            console.error('Unexpected error in editCategory:', err);
        }
    }

    function deleteCategory(categoryId, categoryName) {
        Swal.fire({
            title: `Are you sure you want to delete ${categoryName}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete category
                fetch(`/categories/delete/${categoryId}`, {
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
                            text: data.message || 'Category has been deleted.',
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
                            text: data.message || 'Failed to delete category.'
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

    function updateSlug() {
        const nameInput = document.getElementById('categoryName');
        const slugInput = document.getElementById('categorySlug');
        let slug = nameInput.value.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
        slugInput.value = slug;
    }

    document.getElementById('categoryName').addEventListener('input', updateSlug);
    document.getElementById('categoryName').addEventListener('change', updateSlug);
    document.getElementById('categoryName').addEventListener('blur', updateSlug);
    document.getElementById('categorySlug').addEventListener('input', function() {
        this.value = this.value.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    });
</script>