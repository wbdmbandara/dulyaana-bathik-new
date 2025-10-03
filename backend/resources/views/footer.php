<?php
    include_once 'common/header.php';
    setTitle('Footer');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('appearance', 'footer');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Footer</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Footer</li>
                    </ol>
                </nav>
        </div><!-- End Page Title -->

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

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Manage Footer</h5>
                    </div>

                    <section class="section dashboard">
                        <div class="footer-management">
                            <form id="footerManagementForm" method="post" action="/footer/save" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                                <div class="mb-3">
                                    <label for="siteName" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="siteName" name="siteName" value="<?= $footerContent['site_name'] ?? '' ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="aboutText" class="form-label">About Text</label>
                                    <textarea class="form-control" id="aboutText" name="aboutText" rows="3" required><?= $footerContent['about_text'] ?? '' ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Social Links</label>
                                    <div id="socialLinks">
                                        <?php foreach ($socialLinks ?? [] as $link): ?>
                                            <div class="d-flex mb-2">
                                                <input type="text" class="form-control me-2" name="socialLinks[<?= $link['id'] ?>][platform]" value="<?= $link['platform'] ?>" placeholder="Platform" required>
                                                <input type="url" class="form-control" name="socialLinks[<?= $link['id'] ?>][url]" value="<?= $link['url'] ?>" placeholder="URL" required>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="addSocialLink()">Add Social Link</button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Menu 01 Links</label>
                                    <div id="menu01Links">
                                        <?php foreach ($menu01Links ?? [] as $link): ?>
                                            <div class="d-flex mb-2">
                                                <input type="text" class="form-control me-2" name="menu01Links[<?= $link['id'] ?>][name]" value="<?= $link['title'] ?>" placeholder="Name" required>
                                                <input type="url" class="form-control" name="menu01Links[<?= $link['id'] ?>][url]" value="<?= $link['url'] ?>" placeholder="URL" required>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="addMenu01Link()">Add Menu 01 Link</button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Menu 02 Links</label>
                                    <div id="menu02Links">
                                        <?php foreach ($menu02Links ?? [] as $link): ?>
                                            <div class="d-flex mb-2">
                                                <input type="text" class="form-control me-2" name="menu02Links[<?= $link['id'] ?>][name]" value="<?= $link['title'] ?>" placeholder="Name" required>
                                                <input type="url" class="form-control" name="menu02Links[<?= $link['id'] ?>][url]" value="<?= $link['url'] ?>" placeholder="URL" required>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="addMenu02Link()">Add Menu 02 Link</button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Contact Information</label>
                                    <!-- address input -->
                                    <div class="mb-2">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="contactInfo[address]" value="<?= $footerContent['address'] ?? '' ?>" placeholder="Address" required>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="email01" class="form-label">Email 01</label>
                                            <input type="email" class="form-control" id="email01" name="contactInfo[email01]" value="<?= $footerContent['email01'] ?? '' ?>" placeholder="Email 01" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email02" class="form-label">Email 02</label>
                                            <input type="email" class="form-control" id="email02" name="contactInfo[email02]" value="<?= $footerContent['email02'] ?? '' ?>" placeholder="Email 02">
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="phone01" class="form-label">Phone 01</label>
                                            <input type="tel" class="form-control" id="phone01" name="contactInfo[phone01]" value="<?= $footerContent['phone01'] ?? '' ?>" placeholder="Phone 01" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone02" class="form-label">Phone 02</label>
                                            <input type="tel" class="form-control" id="phone02" name="contactInfo[phone02]" value="<?= $footerContent['phone02'] ?? '' ?>" placeholder="Phone 02">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="openTime" class="form-label">Open Time</label>
                                        <textarea name="contactInfo[openTime]" id="openTime" class="form-control" placeholder="Open Time"><?= $footerContent['open_time'] ?? '' ?></textarea>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">App Links</label>
                                    <div id="appLinks">
                                        <div class="mb-2">
                                            <label for="playStoreLink" class="form-label">Play Store Link</label>
                                            <input type="url" class="form-control" id="playStoreLink" name="appLinks[playStore]" value="<?= $footerContent['play_store_link'] ?? '' ?>" placeholder="Play Store URL">
                                        </div>
                                        <div class="mb-2">
                                            <label for="appStoreLink" class="form-label">App Store Link</label>
                                            <input type="url" class="form-control" id="appStoreLink" name="appLinks[appStore]" value="<?= $footerContent['app_store_link'] ?? '' ?>" placeholder="App Store URL">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Footer Menu</label>
                                    <div id="footerMenu">
                                        <?php foreach ($footerLinks ?? [] as $link): ?>
                                            <div class="d-flex mb-2">
                                                <input type="text" class="form-control me-2" name="footerMenu[<?= $link['id'] ?>][name]" value="<?= $link['title'] ?>" placeholder="Menu Name" required>
                                                <input type="url" class="form-control" name="footerMenu[<?= $link['id'] ?>][url]" value="<?= $link['url'] ?>" placeholder="Menu URL" required>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="addFooterMenu()">Add Footer Menu Item</button>
                                </div>

                                <button type="submit" class="btn btn-primary float-end">Save Footer Content</button>
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
            <input type="url" class="form-control" name="socialLinks[`+id+`][url]" placeholder="URL" required>
        `;
        container.appendChild(div);
    }

    function addMenu01Link() {
        const container = document.getElementById('menu01Links');
        const div = document.createElement('div');
        const count = container.children.length;
        const id = count > 0 ? count+1 : 0;
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control me-2" name="menu01Links[${id}][name]" placeholder="Name" required>
            <input type="url" class="form-control" name="menu01Links[${id}][url]" placeholder="URL" required>
        `;
        container.appendChild(div);
    }

    function addMenu02Link() {
        const container = document.getElementById('menu02Links');
        const div = document.createElement('div');
        const count = container.children.length;
        const id = count > 0 ? count+1 : 0;
        div.className = 'd-flex mb-2';
        div.innerHTML = `
            <input type="text" class="form-control me-2" name="menu02Links[${id}][name]" placeholder="Name" required>
            <input type="url" class="form-control" name="menu02Links[${id}][url]" placeholder="URL" required>
        `;
        container.appendChild(div);
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
            <input type="url" class="form-control" name="footerMenu[${id}][url]" placeholder="URL" required>
        `;
        container.appendChild(div);
    }
</script>