<?php
    include_once 'common/header.php';
    setTitle('Products');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('products', 'products');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Products</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
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
                        <h5 class="card-title mb-0">Manage Products</h5>
                        <a href="<?= url('/new-product'); ?>" class="btn btn-primary">Add New Product</a>
                    </div>

                    <!-- Search product -->
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

                    <!-- products table -->
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
                                <th scope="col">Discounted Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($products as $index => $product): ?>
                                    <tr>
                                        <th class="text-center" scope="row"><?= $index + 1 ?></th>
                                        <td class="text-center">
                                            <img src="<?= htmlspecialchars($product['main_image']) ?>" alt="" class="img-fluid" style="max-height: 50px;" onclick="viewProductImage('<?= htmlspecialchars($product['name']) ?>', '<?= htmlspecialchars($product['main_image']) ?>')">
                                        </td>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                                        <td><?= htmlspecialchars($product['description']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($product['quantity']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($product['price']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($product['discount_price']) ?></td>
                                        <td class="text-center gap-2">
                                            <a href="<?= env('FRONTEND_URL') ?>/product/<?= $product['url'] ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info"><i class="bi bi-box-arrow-up-right"></i></a>
                                            <a href="<?= url('/edit-product/' . $product['item_id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteProduct(<?= $product['item_id'] . ',\'' . $product['name'] . '\'' ?>)"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End products table -->

                    <?= $products->appends(request()->query())->links('custom-pagination') ?>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- viewProductImage -->
    <div class="modal fade" id="viewProductImage" tabindex="-1" aria-labelledby="viewProductImageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProductImageLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="productDetailsContent">
                        <!-- Product details will be loaded here via AJAX -->
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
    function deleteProduct(productId, productTitle) {
        Swal.fire({
            title: `Are you sure you want to delete this product: ${productTitle}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete product
                fetch(`/products/delete/${productId}`, {
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
                            text: data.message || 'Product has been deleted.',
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
                            text: data.message || 'Failed to delete product.'
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

    // viewProductImage
    function viewProductImage(productName, productImageUrl) {
        const modalTitle = document.getElementById('viewProductImageLabel');
        const modalBody = document.getElementById('productDetailsContent');

        modalTitle.textContent = productName + ' - Image Preview';
        modalBody.innerHTML = `<div class="text-center">
                                    <img src="${productImageUrl}" alt="${productName}" class="img-fluid" style="max-height: 400px;">
                               </div>`;
        const viewProductModal = new bootstrap.Modal(document.getElementById('viewProductImage'));
        viewProductModal.show();
    }
</script>