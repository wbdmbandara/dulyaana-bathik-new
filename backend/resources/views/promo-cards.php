<?php
    include_once 'common/header.php';
    setTitle('Promo Cards');
    include_once 'common/topbar.php';
    include_once 'common/sidebar.php';
    setActiveMenuItem('home-page', 'promo-cards');
?>

<style>
    
/*--------------------------------------------------------------
# Promo Cards Section
--------------------------------------------------------------*/
.promo-cards {
  --default-color: #666;
  --heading-color: #333;
}

.promo-cards .promo-card {
  position: relative;
  height: 100%;
  min-height: 400px;
  padding: 2rem;
  border-radius: 0.5rem;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.3s ease;
}

.promo-cards .promo-card:hover {
  transform: translateY(-5px);
}

.promo-cards .promo-card .promo-content {
  position: relative;
  z-index: 2;
  max-width: 80%;
}

.promo-cards .promo-card .small-text {
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
  opacity: 0.8;
}

.promo-cards .promo-card .promo-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 1rem;
  line-height: 1.2;
}

.promo-cards .promo-card .promo-description {
  font-size: 0.95rem;
  margin-bottom: 1.5rem;
  opacity: 0.9;
}

.promo-cards .promo-card .btn-shop {
  display: inline-block;
  position: relative;
  font-weight: 600;
  color: var(--heading-color);
  text-decoration: none;
  padding-bottom: 0.25rem;
  border-bottom: 2px solid var(--heading-color);
  transition: all 0.3s ease;
}

.promo-cards .promo-card .btn-shop:hover {
  color: var(--accent-color);
  border-color: var(--accent-color);
}

.promo-cards .promo-card .promo-image {
  position: absolute;
  bottom: 0;
  right: 0;
  max-width: 50%;
  max-height: 80%;
  display: flex;
  justify-content: flex-end;
  align-items: flex-end;
  z-index: 1;
}

.promo-cards .promo-card .promo-image img {
  max-height: 100%;
  object-fit: contain;
}

.promo-cards .promo-card.card-1 {
  background-color: #f5f2ee;
  color: var(--default-color);
}

.promo-cards .promo-card.card-1 .promo-title {
  color: var(--heading-color);
}

.promo-cards .promo-card.card-2 {
  background-color: #f0f5f0;
  color: var(--default-color);
}

.promo-cards .promo-card.card-2 .promo-title {
  color: var(--heading-color);
}

.promo-cards .promo-card.card-3 {
  background-color: #faf4ea;
  color: var(--default-color);
}

.promo-cards .promo-card.card-3 .promo-title {
  color: var(--heading-color);
}

.promo-cards .promo-card.card-4 {
  background-color: #eef5fa;
  color: var(--default-color);
}

.promo-cards .promo-card.card-4 .promo-title {
  color: var(--heading-color);
}

@media (max-width: 991.98px) {
  .promo-cards .promo-card {
    min-height: 350px;
  }

  .promo-cards .promo-card .promo-content {
    max-width: 100%;
  }

  .promo-cards .promo-card .promo-image {
    max-width: 40%;
    max-height: 70%;
    opacity: 0.9;
  }
}

@media (max-width: 767.98px) {
  .promo-cards .promo-card {
    min-height: 300px;
    padding: 1.5rem;
  }

  .promo-cards .promo-card .promo-title {
    font-size: 1.5rem;
  }

  .promo-cards .promo-card .promo-image {
    max-width: 45%;
    max-height: 65%;
  }
}
</style>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Promo Cards</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Promo Cards</li>
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
                        <h5 class="card-title mb-0">Manage Promo Cards</h5>
                        <button type="button" class="btn btn-primary <?php if(count($allPromoCards) >= 4) echo 'd-none disabled'; ?>" data-bs-toggle="modal" data-bs-target="#newPromoCardModal">Add New Promo Card</button>
                    </div>

                    <section class="section dashboard">            
                        <section id="promo-cards" class="promo-cards section">
                            <p class="text-center text-muted">Note: You can set a maximum of 4 promo cards. It is recommended to set exactly 4 promo cards to properly fill the home page UI layout.</p>
                            <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                                <div class="row g-4">
                                    <?php
                                        if (isset($allPromoCards) && !empty($allPromoCards)) {
                                            foreach ($allPromoCards as $index => $promoCard) {
                                                ?>
                                                <div class="col-md-6 col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                                                    <div class="promo-card card-<?= ($index % 4) + 1 ?>">
                                                        <div class="promo-content">
                                                            <p class="small-text"><?= htmlspecialchars($promoCard['small_text']) ?></p>
                                                            <h3 class="promo-title"><?= htmlspecialchars($promoCard['promo_title']) ?></h3>
                                                            <p class="promo-description"><?= htmlspecialchars($promoCard['promo_description']) ?></p>
                                                            <a href="<?= htmlspecialchars($promoCard['promo_link']) ?>" class="btn-shop" target="_blank"><?= htmlspecialchars($promoCard['button_text']) ?></a>
                                                        </div>
                                                        <div class="promo-image">
                                                            <img alt="Product" class="img-fluid" src="<?= htmlspecialchars($promoCard['promo_image']) ?>">
                                                        </div>
                                                        <div class="position-absolute top-0 end-0 m-2">
                                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editPromoCard(<?= $promoCard['id'] ?>)" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="deletePromoCard(<?= $promoCard['id'] ?>, '<?= htmlspecialchars(addslashes($promoCard['promo_title'])) ?>')" title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        }
                                    ?>
                                    <?php if(isset($promo) && !empty($promo)): ?>
                                        <div class="col-md-6 col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                                            <div class="promo-card card-1">
                                                <div class="promo-content">
                                                    <p class="small-text"><?= $promo['small-text'] ?></p>
                                                    <h3 class="promo-title"><?= $promo['promo-title'] ?></h3>
                                                    <p class="promo-description"><?= $promo['promo-description'] ?></p>
                                                    <a href="<?= $promo['promo-link'] ?>" class="btn-shop" target="_blank"><?= $promo['button-text'] ?></a>
                                                </div>
                                                <div class="promo-image">
                                                    <img alt="Product" class="img-fluid" src="<?= $promo['promo-image'] ?>">
                                                </div>
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <button class="btn btn-sm btn-outline-primary me-1" disabled title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" disabled title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    </section>

                </div>
            </div>

    </main><!-- End #main -->

    <!-- Modal for adding new promo card -->
    <div class="modal fade" id="newPromoCardModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Promo Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <form id="newPromoCardForm" method="post" action="/promo-cards/new" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    
                    <div class="modal-body">
                        <!-- Validation Errors -->
                        <div id="validationErrors" class="alert alert-danger" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>

                        <input type="hidden" id="promoCardId" name="promoCardId" value="">
                        
                        <div class="mb-3">
                            <label for="promoSmallText" class="form-label">Small Text</label>
                            <input type="text" class="form-control" id="promoSmallText" name="smallText">
                        </div>

                        <div class="mb-3">
                            <label for="promoTitle" class="form-label">Promo Title</label>
                            <input type="text" class="form-control" id="promoTitle" name="promoTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="promoDescription" class="form-label">Promo Description</label>
                            <textarea class="form-control" id="promoDescription" name="promoDescription" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="promoLink" class="form-label">Promo Link</label>
                            <input type="url" class="form-control" id="promoLink" name="promoLink" required>
                        </div>

                        <div class="mb-3">
                            <label for="buttonText" class="form-label">Button Text</label>
                            <input type="text" class="form-control" id="buttonText" name="buttonText" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Promo Card Image</label>
                            <div id="imagePreview" class="mb-3" style="display: none;">
                                <img id="previewImg" src="#" alt="Image Preview" class="img-fluid" style="max-width: 100%;">
                            </div>
                            <input type="file" class="form-control" id="promoImage" name="promoImage" accept="image/*" required>
                            <div class="form-text">Recommended size: 1024x1024px</div>
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
        const newPromoCardForm = document.getElementById('newPromoCardForm');
        const validationErrors = document.getElementById('validationErrors');
        const errorList = document.getElementById('errorList');
        const promoCardId = document.getElementById('promoCardId');

        newPromoCardForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Clear previous errors
            validationErrors.style.display = 'none';
            errorList.innerHTML = '';

            // Get form data
            const formData = new FormData(newPromoCardForm);

            let url = '/promo-cards/new';
            if (promoCardId.value) {
                url = `/promo-cards/update/${promoCardId.value}`;
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newPromoCardModal'));
                    modal.hide();
                    newPromoCardForm.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Promo Card saved successfully',
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

    document.getElementById('newPromoCardModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('newPromoCardForm').reset();
        document.getElementById('promoCardId').value = '';
        document.getElementById('validationErrors').style.display = 'none';
        document.getElementById('errorList').innerHTML = '';
        document.querySelector('#newPromoCardModal .modal-title').textContent = 'Add New Promo Card';
    });

    function editPromoCard(promoCardId) {
        try {
            const modal = new bootstrap.Modal(document.getElementById('newPromoCardModal'));
            const modalTitle = document.querySelector('#newPromoCardModal .modal-title');
            modalTitle.textContent = 'Edit Promo Card';
            document.getElementById('promoCardId').value = promoCardId;
            modal.show();

            // Fetch promo card data via AJAX and populate the modal fields
            fetch(`/promo-cards/${promoCardId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const promoCard = data.promoCard;
                    document.getElementById('promoImage').required = false;
                    document.getElementById('previewImg').src = promoCard.promo_image;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('promoSmallText').value = promoCard.small_text;
                    document.getElementById('promoTitle').value = promoCard.promo_title;
                    document.getElementById('promoDescription').value = promoCard.promo_description;
                    document.getElementById('promoLink').value = promoCard.promo_link;
                    document.getElementById('buttonText').value = promoCard.button_text;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch promo card data'
                });
                }
            })
            .catch(error => {
                console.error('Error fetching promo card data:', error);
            });

        } catch (err) {
            console.error('Unexpected error in editPromoCard:', err);
        }
    }

    function deletePromoCard(promoCardId, promoCardTitle) {
        Swal.fire({
            title: `Are you sure you want to delete ${promoCardTitle}?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete promo card
                fetch(`/promo-cards/delete/${promoCardId}`, {
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
                            text: data.message || 'Promo Card has been deleted.',
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
                            text: data.message || 'Failed to delete promo card.'
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

    document.getElementById('promoImage').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const previewImg = document.getElementById('previewImg');
            previewImg.src = URL.createObjectURL(file);
            document.getElementById('imagePreview').style.display = 'block';
        }
    });


</script>