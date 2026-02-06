<?php
    include_once 'common/header.php';
    setTitle('Theme Settings');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('appearance', 'theme-settings');
?>

<style>
    .label-border {
        border-bottom: 1px solid #000;
        padding-bottom: 3px;
    }
    .form-control-color {
        height: 40px;
        width: 40px;
        padding: 2px;
        font-size: 1rem;
        border: 1px solid #0f0f0f;
    }
</style>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Theme Settings</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Theme Settings</li>
                    </ol>
                </nav>
        </div><!-- End Page Title -->

        <div class="card">
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if(session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-2 w-75 mx-auto text-center" role="alert">
                            <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-2 w-75 mx-auto text-center" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Manage Theme Settings</h5>
                    </div>

                    <section class="section dashboard">
                        <div class="theme-color-settings">
                            <form id="themeColorSettingsForm" method="post" action="<?= url('/theme-settings/update') ?>" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="background_color" class="form-label">Background Color</label>
                                        <input type="color" class="form-control form-control-color" id="background_color" name="background_color" value="<?= $themeSettings['background_color'] ?? '#ffffff' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="default_color" class="form-label">Default Text Color</label>
                                        <input type="color" class="form-control form-control-color" id="default_color" name="default_color" value="<?= $themeSettings['default_color'] ?? '#000000' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="heading_color" class="form-label">Heading Color</label>
                                        <input type="color" class="form-control form-control-color" id="heading_color" name="heading_color" value="<?= $themeSettings['heading_color'] ?? '#000000' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="accent_color" class="form-label">Accent Color</label>
                                        <input type="color" class="form-control form-control-color" id="accent_color" name="accent_color" value="<?= $themeSettings['accent_color'] ?? '#ff0000' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="surface_color" class="form-label">Surface Color</label>
                                        <input type="color" class="form-control form-control-color" id="surface_color" name="surface_color" value="<?= $themeSettings['surface_color'] ?? '#f8f9fa' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="contrast_color" class="form-label">Contrast Color</label>
                                        <input type="color" class="form-control form-control-color" id="contrast_color" name="contrast_color" value="<?= $themeSettings['contrast_color'] ?? '#000000' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="nav_color" class="form-label">Navigation Text Color</label>
                                        <input type="color" class="form-control form-control-color" id="nav_color" name="nav_color" value="<?= $themeSettings['nav_color'] ?? '#ffffff' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="nav_hover_color" class="form-label">Navigation Hover Color</label>
                                        <input type="color" class="form-control form-control-color" id="nav_hover_color" name="nav_hover_color" value="<?= $themeSettings['nav_hover_color'] ?? '#cccccc' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="nav_mobile_background_color" class="form-label">Mobile Navigation Background Color</label>
                                        <input type="color" class="form-control form-control-color" id="nav_mobile_background_color" name="nav_mobile_background_color" value="<?= $themeSettings['nav_mobile_background_color'] ?? '#000000' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="nav_dropdown_background_color" class="form-label">Dropdown Background Color</label>
                                        <input type="color" class="form-control form-control-color" id="nav_dropdown_background_color" name="nav_dropdown_background_color" value="<?= $themeSettings['nav_dropdown_background_color'] ?? '#ffffff' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="nav_dropdown_color" class="form-label">Dropdown Text Color</label>
                                        <input type="color" class="form-control form-control-color" id="nav_dropdown_color" name="nav_dropdown_color" value="<?= $themeSettings['nav_dropdown_color'] ?? '#000000' ?>" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="nav_dropdown_hover_color" class="form-label">Dropdown Hover Color</label>
                                        <input type="color" class="form-control form-control-color" id="nav_dropdown_hover_color" name="nav_dropdown_hover_color" value="<?= $themeSettings['nav_dropdown_hover_color'] ?? '#cccccc' ?>" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary float-end">Save Theme Colors</button>
                            </form>
                        </div>
                    </section>

                </div>
            </div>

    </main><!-- End #main -->

<?php
    include_once 'common/footer.php';
?>

<script>
    function addSocialLink() {
        const container = document.getElementById('socialLinks');
        const div = document.createElement('div');
        const count = container.children.length;
        const id = count > 0 ? count+1 : 0;
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control me-2" name="socialLinks[`+id+`][platform]" placeholder="Platform" required>
            <input type="url" class="form-control me-2" name="socialLinks[`+id+`][url]" placeholder="URL" required>
            <input type="text" class="form-control me-2" name="socialLinks[`+id+`][icon]" placeholder="Bootstrap Icon Class" required>
            <span class="m-1 p-1 me-2"><i class=""></i></span>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSocialLink(this)"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function removeSocialLink(button) {
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const div = button.parentElement;
                div.remove();
                swal.fire(
                    'Removed!',
                    'The link will be removed after saving these changes.',
                    'success'
                )
            }
        })
    }

    function addMenu01Link() {
        const container = document.getElementById('menu01Links');
        const div = document.createElement('div');
        const count = container.children.length;
        const id = count > 0 ? count+1 : 0;
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control me-2" name="menu01Links[${id}][name]" placeholder="Name" required>
            <input type="url" class="form-control me-2" name="menu01Links[${id}][url]" placeholder="URL" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeMenu01Link(this)"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function removeMenu01Link(button) {
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const div = button.parentElement;
                div.remove();
                swal.fire(
                    'Removed!',
                    'The link will be removed after saving these changes.',
                    'success'
                )
            }
        })
    }

    function addMenu02Link() {
        const container = document.getElementById('menu02Links');
        const div = document.createElement('div');
        const count = container.children.length;
        const id = count > 0 ? count+1 : 0;
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control me-2" name="menu02Links[${id}][name]" placeholder="Name" required>
            <input type="url" class="form-control me-2" name="menu02Links[${id}][url]" placeholder="URL" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeMenu02Link(this)"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function removeMenu02Link(button) {
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const div = button.parentElement;
                div.remove();
                swal.fire(
                    'Removed!',
                    'The link will be removed after saving these changes.',
                    'success'
                )
            }
        })
    }

    function addPaymentMethod() {
        const container = document.getElementById('paymentMethods');
        const div = document.createElement('div');
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="paymentMethods[]" placeholder="Payment Method" required>
        `;
        container.appendChild(div);
    }

    function addFooterMenu() {
        const container = document.getElementById('footerMenu');
        const div = document.createElement('div');
        const count = container.children.length;
        const id = count > 0 ? count+1 : 0;
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control me-2" name="footerMenu[${id}][name]" placeholder="Name" required>
            <input type="url" class="form-control me-2" name="footerMenu[${id}][url]" placeholder="URL" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeFooterMenu(this)"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function removeFooterMenu(button) {
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const div = button.parentElement;
                div.remove();
                swal.fire(
                    'Removed!',
                    'The menu item will be removed after saving these changes.',
                    'success'
                )
            }
        })
    }
</script>