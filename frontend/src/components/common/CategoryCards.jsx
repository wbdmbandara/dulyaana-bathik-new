import React from 'react'

function CategoryCards() {
  return (
    <div>
        {/* Category Cards Section */}
    <section id="category-cards" className="category-cards section light-background">

      <div className="container" data-aos="fade-up" data-aos-delay="100">

        <div className="category-tabs">
          <ul className="nav justify-content-center" id="category-cards-tabs" role="tablist">
            <li className="nav-item" role="presentation">
              <button className="nav-link" id="category-cards-men-tab" data-bs-toggle="tab" data-bs-target="#category-cards-men-content" type="button" role="tab" aria-controls="category-cards-men-content" aria-selected="false">SHOP MEN</button>
            </li>
            <li className="nav-item" role="presentation">
              <button className="nav-link active" id="category-cards-women-tab" data-bs-toggle="tab" data-bs-target="#category-cards-women-content" type="button" role="tab" aria-controls="category-cards-women-content" aria-selected="true">SHOP WOMEN</button>
            </li>
            <li className="nav-item" role="presentation">
              <button className="nav-link" id="category-cards-accesoires-tab" data-bs-toggle="tab" data-bs-target="#category-cards-accesoires-content" type="button" role="tab" aria-controls="category-cards-accesoires-content" aria-selected="false">SHOP ACCESSORIES</button>
            </li>
          </ul>
        </div>

        <div className="tab-content" id="category-cards-tabContent">
          {/* Men's Categories */}
          <div className="tab-pane fade" id="category-cards-men-content" role="tabpanel" aria-labelledby="category-cards-men-tab">
            <div className="row g-4">
              {/* Leather Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div className="category-card">
                  <img src="assets/img/product/product-m-11.webp" alt="Men's Leather" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    LEATHER <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              {/* Denim Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div className="category-card">
                  <img src="assets/img/product/product-m-12.webp" alt="Men's Denim" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    DENIM <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              {/* Swimwear Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div className="category-card">
                  <img src="assets/img/product/product-m-19.webp" alt="Men's Swimwear" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    SWIMWEAR <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          {/* Women's Categories */}
          <div className="tab-pane fade show active" id="category-cards-women-content" role="tabpanel" aria-labelledby="category-cards-women-tab">
            <div className="row g-4">
              {/* Dresses Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div className="category-card">
                  <img src="assets/img/product/product-f-11.webp" alt="Women's Dresses" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    DRESSES <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              {/* Tops Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div className="category-card">
                  <img src="assets/img/product/product-f-18.webp" alt="Women's Tops" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    TOPS <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              {/* Accessories Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div className="category-card">
                  <img src="assets/img/product/product-f-13.webp" alt="Women's Accessories" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    ACCESSORIES <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          {/* Kid's Categories */}
          <div className="tab-pane fade" id="category-cards-accesoires-content" role="tabpanel" aria-labelledby="category-cards-accesoires-tab">
            <div className="row g-4">
              {/* Boys Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div className="category-card">
                  <img src="assets/img/product/product-1.webp" alt="Boys Clothing" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    BOYS <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              {/* Girls Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div className="category-card">
                  <img src="assets/img/product/product-2.webp" alt="Girls Clothing" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    GIRLS <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              {/* Toys Category */}
              <div className="col-12 col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div className="category-card">
                  <img src="assets/img/product/product-3.webp" alt="Kids Toys" className="img-fluid" loading="lazy" />
                  <a href="#" className="category-link">
                    TOYS <i className="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section>{/* /Category Cards Section */}
    </div>
  )
}

export default CategoryCards