<?php
    include_once 'common/header.php';
    setTitle('New Saree');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('sarees', 'new-saree');
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Sarees</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">New Saree</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">            
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-1">
                        <h5 class="card-title mb-0">Add New Saree</h5>
                        <a href="/sarees" class="btn btn-primary">View All Sarees</a>
                    </div>

                    <!-- New Saree Form -->
                    <form action="/sarees/new" method="post" enctype="multipart/form-data" class="row" id="newSareeForm">
                        <!-- Success/Error Messages -->
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <?= session('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php session()->forget('success'); ?>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <?= session('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php session()->forget('error'); ?>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <ul class="mb-0">
                                    <?php foreach($errors->all() as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="name" class="form-label">Title</label>
                                <input type="text" class="form-control" id="name" name="name" required value="<?= old('name') ?>">
                            </div>
                            <div class="mb-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description') ?></textarea>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" required value="<?= old('price') ?>">
                                </div>
                                <div class="col-4">
                                    <label for="discount_price" class="form-label">Discount Price</label>
                                    <input type="text" class="form-control" id="discount_price" name="discount_price" value="<?= old('discount_price') ?>">
                                </div>
                                <div class="col-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" required value="<?= old('quantity') ?>">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <?php
                                        foreach ($categories as $category) {
                                            $selected = (old('category') == $category['id']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($category['id']) . '" ' . $selected . '>' . htmlspecialchars($category['cat_name']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="" hidden>Select Status</option>
                                        <option value="active" <?= old('status') == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label for="fabric" class="form-label">Fabric</label>
                                    <input type="text" class="form-control" id="fabric" name="fabric" value="<?= old('fabric') ?>">
                                </div>
                                <div class="col-6">
                                    <label for="pattern" class="form-label">Pattern</label>
                                    <input type="text" class="form-control" id="pattern" name="pattern" value="<?= old('pattern') ?>">
                                </div>
                            </div>
                            <div class="mb-2 d-none">
                                <label for="saree_work" class="form-label">Saree Work</label>
                                <input type="text" class="form-control" id="saree_work" name="saree_work" value="<?= old('saree_work') ?>">
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="saree_length" class="form-label">Saree Length</label>
                                    <input type="text" class="form-control" id="saree_length" name="saree_length" value="<?= old('saree_length') ?>">
                                </div>
                                <div class="col-4">
                                    <label for="blouse_length" class="form-label">Blouse Length</label>
                                    <input type="text" class="form-control" id="blouse_length" name="blouse_length" value="<?= old('blouse_length') ?>">
                                </div>
                                <div class="col-4">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" class="form-control" id="weight" name="weight" value="<?= old('weight') ?>">
                                </div>
                            </div>
                            <div class="mb-2 d-none">
                                <label for="set_contents" class="form-label">Set Contents</label>
                                <input type="text" class="form-control" id="set_contents" name="set_contents" value="<?= old('set_contents') ?>">
                            </div>
                            <div class="mb-2 d-none">
                                <label for="occasion" class="form-label">Occasion</label>
                                <input type="text" class="form-control" id="occasion" name="occasion" value="<?= old('occasion') ?>">
                            </div>
                            <div class="mb-2">
                                <label for="wash_care" class="form-label">Wash Care</label>
                                <input type="text" class="form-control" id="wash_care" name="wash_care" value="<?= old('wash_care') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="url" class="form-label">URL Slug</label>
                                <input type="text" class="form-control" id="url" name="url" required value="<?= old('url') ?>">
                            </div>
                            <div class="mb-2">
                                <label for="mainImage" class="form-label">Main Image</label>
                                <?php if(session()->has('temp_image') && file_exists(public_path(session('temp_image')))): ?>
                                    <div class="mb-2" id="existingImageContainer">
                                        <p class="text-muted small">Current image:</p>
                                        <img src="/<?= session('temp_image') ?>" alt="Current Image" class="img-fluid mb-2" style="max-height: 300px;">
                                        <br>
                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeExistingImage()">Remove Current Image</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="existing_main_image" value="<?= session('temp_image') ?>">
                                <?php endif; ?>
                                <div class="mb-2" id="imagePreviewContainer" style="display:none;">
                                    <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid mb-2" style="max-height: 300px;">
                                    <br>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-danger btn-sm" id="removeImageBtn">Remove Image</button>
                                    </div>
                                </div>
                                <input class="form-control" type="file" id="mainImage" name="main_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                            </div>
                            <div class="mb-2">
                                <label for="additionalImages" class="form-label">Additional Images</label>
                                <div class="alert alert-danger" id="additionalImagesError" style="display:none;"></div>
                                <?php if(session()->has('temp_additional_images') && !empty(session('temp_additional_images'))): ?>
                                    <div class="mb-2" id="existingAdditionalImagesContainer">
                                        <p class="text-muted small">Current additional images:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach(session('temp_additional_images') as $index => $imagePath): ?>
                                                <?php if(file_exists(public_path($imagePath))): ?>
                                                    <div class="position-relative">
                                                        <img src="/<?= $imagePath ?>" alt="Additional Image" class="img-thumbnail" style="max-height: 150px; max-width: 150px;">
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeExistingAdditionalImage(this, <?= $index ?>)" style="transform: translate(25%, -25%);">×</button>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="existing_additional_images" value="<?= htmlspecialchars(json_encode(session('temp_additional_images'))) ?>">
                                    </div>
                                <?php endif; ?>
                                <div id="additionalImagesContainer"></div>
                                <input class="form-control" type="file" id="additionalImages" name="additional_images[]" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml" multiple>
                                <small class="text-muted">You can select up to 20 additional images (JPEG, PNG, JPG, GIF, SVG formats, max 5MB each)</small>
                            </div>
                            <div class="mb-2">
                                <label for="videos" class="form-label">Videos</label>
                                <div class="alert alert-danger" id="videosError" style="display:none;"></div>
                                <?php if(session()->has('temp_videos') && !empty(session('temp_videos'))): ?>
                                    <div class="mb-2" id="existingVideosContainer">
                                        <p class="text-muted small">Current videos:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach(session('temp_videos') as $index => $videoPath): ?>
                                                <?php if(file_exists(public_path($videoPath))): ?>
                                                    <div class="position-relative border rounded p-2" style="min-width: 200px;">
                                                        <video controls style="max-height: 150px;">
                                                            <source src="/<?= $videoPath ?>" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeExistingVideo(this, <?= $index ?>)" style="transform: translate(25%, -25%);">×</button>
                                                        <small class="text-muted d-block mt-1"><?= basename($videoPath) ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="existing_videos" value="<?= htmlspecialchars(json_encode(session('temp_videos'))) ?>">
                                    </div>
                                <?php endif; ?>
                                <div id="videosContainer"></div>
                                <input class="form-control" type="file" id="videos" name="videos[]" accept="video/mp4,video/webm,video/ogg,video/avi,video/mov,video/wmv" multiple>
                                <small class="text-muted">You can select up to 5 videos (MP4, WebM, OGG, AVI, MOV, WMV formats, max 50MB each)</small>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button type="submit" class="btn btn-success">Add Saree</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>

    </main><!-- End #main -->


<?php
    include_once 'common/footer.php';
?>

<script>
    document.getElementById('mainImage').addEventListener('change', function(event) {
        removeExistingImage();
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                document.getElementById('imagePreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('removeImageBtn').addEventListener('click', function() {
        document.getElementById('mainImage').value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
    });

    // generate URL slug from name
    document.getElementById('name').addEventListener('input', generateSlug);
    document.getElementById('name').addEventListener('change', generateSlug);
    document.getElementById('name').addEventListener('blur', generateSlug);

    function generateSlug() {
        const name = document.getElementById('name').value;
        const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        document.getElementById('url').value = slug;
    }

    function removeExistingImage() {
        const existingImageContainer = document.getElementById('existingImageContainer');
        if (existingImageContainer) {
            existingImageContainer.remove();
        }
        const existingImageInput = document.querySelector('input[name="existing_main_image"]');
        if (existingImageInput) {
            existingImageInput.remove();
        }
    }
    
    document.getElementById('additionalImages').addEventListener('change', function(event) {
        const container = document.getElementById('additionalImagesContainer');
        const errorDiv = document.getElementById('additionalImagesError');
        errorDiv.style.display = 'none';
        errorDiv.innerHTML = '';
        
        const files = event.target.files;
        const maxImages = 20;
        const existingImagesCount = document.querySelectorAll('#existingAdditionalImagesContainer .position-relative').length;
        const currentPreviewsCount = document.querySelectorAll('#additionalImagesContainer .mb-2').length;
        const totalImages = existingImagesCount + currentPreviewsCount + files.length;
        
        if (totalImages > maxImages) {
            errorDiv.innerHTML = `<div>You can only upload up to ${maxImages} additional images. You currently have ${existingImagesCount + currentPreviewsCount} images.</div>`;
            errorDiv.style.display = 'block';
            event.target.value = '';
            return;
        }
        
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    errorDiv.innerHTML += `<div>${file.name} is not a valid image format. Allowed formats: JPEG, PNG, JPG, GIF, SVG</div>`;
                    errorDiv.style.display = 'block';
                    continue;
                }

                // Validate file size (5MB = 5120KB = 5242880 bytes)
                const maxSize = 5242880; // 5MB in bytes
                if (file.size > maxSize) {
                    errorDiv.innerHTML += `<div>${file.name} is too large. Maximum file size is 5MB</div>`;
                    errorDiv.style.display = 'block';
                    continue;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'mb-2 d-inline-block me-2';
                    imageDiv.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" alt="Additional Image" class="img-thumbnail" style="max-height: 150px; max-width: 150px;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeAdditionalImage(this)" style="transform: translate(25%, -25%);">×</button>
                        </div>
                    `;
                    container.appendChild(imageDiv);
                };
                
                reader.readAsDataURL(file);
            }
        }
    });

    document.getElementById('videos').addEventListener('change', function(event) {
        const container = document.getElementById('videosContainer');
        const errorDiv = document.getElementById('videosError');
        errorDiv.style.display = 'none';
        errorDiv.innerHTML = '';
        
        const files = event.target.files;
        const maxVideos = 5;
        const existingVideosCount = document.querySelectorAll('#existingVideosContainer .position-relative').length;
        const currentPreviewsCount = document.querySelectorAll('#videosContainer .mb-2').length;
        const totalVideos = existingVideosCount + currentPreviewsCount + files.length;
        
        if (totalVideos > maxVideos) {
            errorDiv.innerHTML = `<div>You can only upload up to ${maxVideos} videos. You currently have ${existingVideosCount + currentPreviewsCount} videos.</div>`;
            errorDiv.style.display = 'block';
            event.target.value = '';
            return;
        }
        
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Validate file type
                const allowedTypes = ['video/mp4', 'video/webm', 'video/ogg', 'video/avi', 'video/mov', 'video/wmv', 'video/quicktime', 'video/x-msvideo'];
                if (!allowedTypes.includes(file.type)) {
                    errorDiv.innerHTML += `<div>${file.name} is not a valid video format. Allowed formats: MP4, WebM, OGG, AVI, MOV, WMV</div>`;
                    errorDiv.style.display = 'block';
                    continue;
                }

                // Validate file size (50MB = 52428800 bytes)
                const maxSize = 52428800; // 50MB in bytes
                if (file.size > maxSize) {
                    errorDiv.innerHTML += `<div>${file.name} is too large. Maximum file size is 50MB</div>`;
                    errorDiv.style.display = 'block';
                    continue;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const videoDiv = document.createElement('div');
                    videoDiv.className = 'mb-2 d-inline-block me-2';
                    videoDiv.innerHTML = `
                        <div class="position-relative border rounded p-2" style="min-width: 200px;">
                            <video controls style="max-height: 150px;">
                                <source src="${e.target.result}" type="${file.type}">
                                Your browser does not support the video tag.
                            </video>
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeVideo(this)" style="transform: translate(25%, -25%);">×</button>
                            <small class="text-muted d-block mt-1">${file.name}</small>
                        </div>
                    `;
                    container.appendChild(videoDiv);
                };
                
                reader.readAsDataURL(file);
            }
        }
    });
    
    function removeAdditionalImage(button) {
        button.closest('.mb-2').remove();
    }

    function removeVideo(button) {
        button.closest('.mb-2').remove();
    }

    function removeExistingVideo(button, index) {
        button.closest('.position-relative').remove();
        const existingVideosInput = document.querySelector('input[name="existing_videos"]');
        if (existingVideosInput) {
            let existingVideos = JSON.parse(existingVideosInput.value);
            existingVideos.splice(index, 1);
            if (existingVideos.length > 0) {
                existingVideosInput.value = JSON.stringify(existingVideos);
            } else {
                existingVideosInput.remove();
                const existingContainer = document.getElementById('existingVideosContainer');
                if (existingContainer) {
                    existingContainer.remove();
                }
            }
        }
    }

    function removeExistingAdditionalImage(button, index) {
        button.closest('.position-relative').remove();
        const existingImagesInput = document.querySelector('input[name="existing_additional_images"]');
        if (existingImagesInput) {
            let existingImages = JSON.parse(existingImagesInput.value);
            existingImages.splice(index, 1);
            if (existingImages.length > 0) {
                existingImagesInput.value = JSON.stringify(existingImages);
            } else {
                existingImagesInput.remove();
                const existingContainer = document.getElementById('existingAdditionalImagesContainer');
                if (existingContainer) {
                    existingContainer.remove();
                }
            }
        }
    }
</script>