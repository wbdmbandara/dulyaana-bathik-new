<?php
    include_once 'common/header.php';
    setTitle('Home Slider');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('home-page', 'home-slider');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Home Slider</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Home Slider</li>
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
                        <h5 class="card-title mb-0">Manage Home Slider</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newSlideModal">Add New Slide</button>
                    </div>

                    <!-- Slider table -->
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Alt Text</th>
                                <th scope="col">Order</th>
                                <th scope="col">Visibility</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($sliders as $index => $slider): ?>
                                    <tr class="align-middle text-center">
                                        <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                        <td><img src="<?= htmlspecialchars($slider['image_path']) ?>" alt="<?= htmlspecialchars($slider['alt_text']) ?>" class="img-fluid" style="max-width: 100px;"></td>
                                        <td><?= htmlspecialchars($slider['alt_text']) ?></td>
                                        <td><?= htmlspecialchars($slider['order']) ?></td>
                                        <td><?= $slider['visibility'] ? 'Visible' : 'Hidden' ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning editbtn" onclick="editSlide(<?= $slider['id'] ?>)"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteSlide(<?= $slider['id'] . ',\'' . $slider['alt_text'] . '\'' ?>)"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End slider table -->

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal for adding new slide -->
    <div class="modal fade" id="newSlideModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <form id="newSlideForm" method="post" action="<?= url('/slides/new') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    
                    <div class="modal-body">
                        <!-- Validation Errors -->
                        <div id="validationErrors" class="alert alert-danger" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>
                        
                        <input type="hidden" id="slideId" name="slideId" value="">
                        <div class="mb-3">
                            <label for="slideImage" class="form-label">Slide Image</label>
                            <div id="imagePreview" class="mb-3" style="display: none;">
                                <img id="previewImg" src="#" alt="Image Preview" class="img-fluid" style="max-width: 100%;">
                            </div>
                            <input type="file" class="form-control" id="slideImage" name="slideImage" accept="image/*" required>
                            <div class="form-text">Recommended size: 1400x480px</div>
                        </div>

                        <div class="mb-3">
                            <label for="altText" class="form-label">Alt Text</label>
                            <input type="text" class="form-control" id="altText" name="altText" required>
                            <div class="invalid-feedback">
                                Please provide a valid alt text.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="slideOrder" class="form-label">Order</label>
                            <input type="number" class="form-control" id="slideOrder" name="slideOrder" min="1" required>
                            <div class="invalid-feedback">
                                Please provide a valid order number.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="slideVisibility" class="form-label">Visibility</label>
                            <select class="form-select" id="slideVisibility" name="slideVisibility" required>
                                <option value="" hidden selected>Select Visibility</option>
                                <option value="1">Visible</option>
                                <option value="0">Hidden</option>
                            </select>
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
        const newSlideForm = document.getElementById('newSlideForm');
        const validationErrors = document.getElementById('validationErrors');
        const errorList = document.getElementById('errorList');
        const slideId = document.getElementById('slideId');

        newSlideForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Clear previous errors
            validationErrors.style.display = 'none';
            errorList.innerHTML = '';

            // Get form data
            const formData = new FormData(newSlideForm);

            let url = '/slides/new';
            if (slideId.value) {
                url = `/slides/update/${slideId.value}`;
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newSlideModal'));
                    modal.hide();
                    newSlideForm.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Slide saved successfully',
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

    document.getElementById('newSlideModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('newSlideForm').reset();
        document.getElementById('slideId').value = '';
        document.getElementById('validationErrors').style.display = 'none';
        document.getElementById('errorList').innerHTML = '';
        document.querySelector('#newSlideModal .modal-title').textContent = 'Add New Slide';
    });

    function editSlide(slideId) {
        try {
            const modal = new bootstrap.Modal(document.getElementById('newSlideModal'));
            const modalTitle = document.querySelector('#newSlideModal .modal-title');
            modalTitle.textContent = 'Edit Slide';
            document.getElementById('slideId').value = slideId;
            modal.show();

            // Fetch slide data via AJAX and populate the modal fields
            fetch(`/slides/${slideId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const slide = data.slide;
                    document.getElementById('slideImage').required = false;
                    document.getElementById('previewImg').src = slide.image_path;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('altText').value = slide.alt_text;
                    document.getElementById('slideOrder').value = slide.order;
                    document.getElementById('slideVisibility').value = slide.visibility;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch slide data'
                });
                }
            })
            .catch(error => {
                console.error('Error fetching slide data:', error);
            });

        } catch (err) {
            console.error('Unexpected error in editSlide:', err);
        }
    }

    function deleteSlide(slideId, slideTitle) {
        Swal.fire({
            title: `Are you sure you want to delete ${slideTitle}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete slide
                fetch(`/slides/delete/${slideId}`, {
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
                            text: data.message || 'Slide has been deleted.',
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
                            text: data.message || 'Failed to delete slide.'
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

    document.getElementById('slideImage').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const previewImg = document.getElementById('previewImg');
            previewImg.src = URL.createObjectURL(file);
            document.getElementById('imagePreview').style.display = 'block';
        }
    });


</script>