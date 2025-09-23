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
                                        <td><?= htmlspecialchars($saree['name']) ?></td>
                                        <td class="text-center">
                                            <img src="<?= htmlspecialchars($saree['main_image']) ?>" alt="" class="img-fluid" style="max-height: 150px;">
                                        </td>
                                        <td><?= htmlspecialchars($saree['category_name']) ?></td>
                                        <td><?= htmlspecialchars($saree['description']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($saree['quantity']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($saree['price']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($saree['discount_price']) ?></td>
                                        <td class="text-center d-flex flex-column gap-2">
                                            <button class="btn btn-sm btn-warning editbtn" onclick="editSaree(<?= $saree['id'] ?>)"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="btn btn-sm btn-danger deletebtn" onclick="deleteSaree(<?= $saree['id'] . ',\'' . $saree['name'] . '\'' ?>)"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End sarees table -->

                    <?= $sarees->links('custom-pagination') ?>
                </div>
            </div>
        </section>

    </main><!-- End #main -->


<?php
    include_once 'common/footer.php';
?>
