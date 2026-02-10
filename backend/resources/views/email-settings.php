<?php
    include_once 'common/header.php';
    setTitle('Email Settings');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('settings', 'email-settings');
?>

<style>
    .label-border {
        border-bottom: 1px solid #000;
        padding-bottom: 3px;
    }
</style>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Email Settings</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Email Settings</li>
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
                        <h5 class="card-title mb-0">Manage Email Settings</h5>
                    </div>

                    <section class="section dashboard">
                        <form action="<?= url('/update-email-settings') ?>" method="POST">
                            <div class="row mb-3">
                                <label for="mail_mailer" class="col-sm-2 col-form-label">Mail Mailer</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                    <input type="text" class="form-control" id="mail_mailer" name="mail_mailer" value="<?= htmlspecialchars($settings['mail_mailer'] ?? 'smtp') ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mail_host" class="col-sm-2 col-form-label">Mail Host</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mail_host" name="mail_host" value="<?= htmlspecialchars($settings['mail_host'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mail_port" class="col-sm-2 col-form-label">Mail Port</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mail_port" name="mail_port" value="<?= htmlspecialchars($settings['mail_port'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mail_username" class="col-sm-2 col-form-label">Mail Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mail_username" name="mail_username" value="<?= htmlspecialchars($settings['mail_username'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mail_password" class="col-sm-2 col-form-label">Mail Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="mail_password" name="mail_password" value="<?= htmlspecialchars($settings['mail_password'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3 d-none">
                                <label for="mail_encryption" class="col-sm-2 col-form-label">Mail Encryption</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mail_encryption" name="mail_encryption" value="<?= htmlspecialchars($settings['mail_encryption'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mail_from_address" class="col-sm-2 col-form-label">Mail From Address</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" value="<?= htmlspecialchars($settings['mail_from_address'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mail_from_name" class="col-sm-2 col-form-label">Mail From Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" value="<?= htmlspecialchars($settings['mail_from_name'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="admin_notification_email" class="col-sm-2 col-form-label">Admin Notification Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="admin_notification_email" name="admin_notification_email" value="<?= htmlspecialchars($settings['admin_notification_email'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="float-end">
                                <button type="submit" class="btn btn-primary">Update Email Settings</button>
                            </div>
                        </form>
                    </section>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                <section class="section dashboard mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Test Email Configuration</h5>
                        </div>
                        <form action="<?= url('/send-test-email') ?>" method="POST">
                            <div class="row mb-3">
                                <label for="test_email" class="col-sm-2 col-form-label">Test Email</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                    <input type="email" class="form-control" id="test_email" name="test_email" required>
                                </div>
                            </div>
                            <div class="float-end">
                                <button type="submit" class="btn btn-primary">Send Test Email</button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

    </main><!-- End #main -->

<?php
    include_once 'common/footer.php';
?>
