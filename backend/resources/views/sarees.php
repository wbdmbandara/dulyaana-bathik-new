<?php
    include_once 'common/header.php';
    setTitle('Sarees');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('sarees', 'sarees');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Sarees</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Sarees</li>
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
                        <h5 class="card-title mb-0">Manage Sarees</h5>
                        <a href="/new-saree" class="btn btn-primary">Add New Saree</a>
                    </div>

                    <!-- Search Saree -->
                    <form action="" method="GET" class="mt-3">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="search" placeholder="Search by Title, Category or Description" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="hide_zero_qty" id="hideZeroQty" value="1" <?= isset($_GET['hide_zero_qty']) && $_GET['hide_zero_qty'] == '1' ? 'checked' : '' ?>>
                                <label class="form-check-label ms-1" for="hideZeroQty">Hide zero qty</label>
                            </div>
                            <button class="btn btn-primary" type="submit">Search</button>
                            <button type="button" class="btn btn-danger" onclick="location.href='?search='">Reset</button>
                        </div>
                    </form>

                    <!-- sarees table -->
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Description</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Discount Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($sarees as $index => $saree): ?>
                                    <tr>
                                        <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                        <td class="text-center">
                                            <img src="<?= htmlspecialchars($saree['main_image']) ?>" alt="" class="img-fluid" style="max-height: 50px;" onclick="viewSareeImage('<?= htmlspecialchars($saree['name']) ?>', '<?= htmlspecialchars($saree['main_image']) ?>')">
                                        </td>
                                        <td><?= htmlspecialchars($saree['name']) ?></td>
                                        <td><?= htmlspecialchars($saree['category_name']) ?></td>
                                        <td><?= htmlspecialchars($saree['description']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($saree['quantity']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($saree['price']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($saree['discount_price']) ?></td>
                                        <td class="text-center gap-2">
                                            <a href="<?= env('FRONTEND_URL') ?>/product/<?= $saree['url'] ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info"><i class="bi bi-box-arrow-up-right"></i></a>
                                            <a href="/edit-saree/<?= $saree['item_id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteSaree(<?= $saree['item_id'] . ',\'' . $saree['name'] . '\'' ?>)"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End sarees table -->

                    <?= $sarees->appends(request()->query())->links('custom-pagination') ?>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- viewSareeImage -->
    <div class="modal fade" id="viewSareeImage" tabindex="-1" aria-labelledby="viewSareeImageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSareeImageLabel">Saree Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="sareeDetailsContent">
                        <!-- Saree details will be loaded here via AJAX -->
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
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
    function deleteSaree(sareeId, sareeTitle) {
        Swal.fire({
            title: `Are you sure you want to delete this saree: ${sareeTitle}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete saree
                fetch(`/sarees/delete/${sareeId}`, {
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
                            text: data.message || 'Saree has been deleted.',
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
                            text: data.message || 'Failed to delete saree.'
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

    // viewSareeImage
    function viewSareeImage(sareeName, sareeImageUrl) {
        const modalTitle = document.getElementById('viewSareeImageLabel');
        const modalBody = document.getElementById('sareeDetailsContent');

        modalTitle.textContent = sareeName + ' - Image Preview';
        modalBody.innerHTML = `<div class="text-center">
                                    <img src="${sareeImageUrl}" alt="${sareeName}" class="img-fluid" style="max-height: 400px;">
                               </div>`;
        const viewSareeModal = new bootstrap.Modal(document.getElementById('viewSareeImage'));
        viewSareeModal.show();
    }
</script>