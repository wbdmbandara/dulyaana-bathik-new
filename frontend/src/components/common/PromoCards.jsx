import React from 'react'

function PromoCards() {
  return (
    <div>
        {/* Promo Cards Section */}
    <section id="promo-cards" className="promo-cards section">

      <div className="container" data-aos="fade-up" data-aos-delay="100">

        <div className="row g-4">
          {/* Promo Card 1 */}
          <div className="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div className="promo-card card-1">
              <div className="promo-content">
                <p className="small-text">Etiam vel augue</p>
                <h3 className="promo-title">Nullam quis ante</h3>
                <p className="promo-description">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu in enim justo rhoncus ut.</p>
                <a href="#" className="btn-shop">Shop Now</a>
              </div>
              <div className="promo-image">
                <img src="assets/img/product/product-1.webp" alt="Product" className="img-fluid" />
              </div>
            </div>
          </div>

          {/* Promo Card 2 */}
          <div className="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div className="promo-card card-2">
              <div className="promo-content">
                <p className="small-text">Maecenas tempus</p>
                <h3 className="promo-title">Sed fringilla mauris</h3>
                <p className="promo-description">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu in enim justo rhoncus ut.</p>
                <a href="#" className="btn-shop">Shop Now</a>
              </div>
              <div className="promo-image">
                <img src="assets/img/product/product-2.webp" alt="Product" className="img-fluid" />
              </div>
            </div>
          </div>

          {/* Promo Card 3 */}
          <div className="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
            <div className="promo-card card-3">
              <div className="promo-content">
                <p className="small-text">Aenean commodo</p>
                <h3 className="promo-title">Fusce vulputate eleifend</h3>
                <p className="promo-description">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu in enim justo rhoncus ut.</p>
                <a href="#" className="btn-shop">Shop Now</a>
              </div>
              <div className="promo-image">
                <img src="assets/img/product/product-f-1.webp" alt="Product" className="img-fluid" />
              </div>
            </div>
          </div>

          {/* Promo Card 4 */}
          <div className="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
            <div className="promo-card card-4">
              <div className="promo-content">
                <p className="small-text">Pellentesque auctor</p>
                <h3 className="promo-title">Vestibulum dapibus nunc</h3>
                <p className="promo-description">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu in enim justo rhoncus ut.</p>
                <a href="#" className="btn-shop">Shop Now</a>
              </div>
              <div className="promo-image">
                <img src="assets/img/product/product-m-1.webp" alt="Product" className="img-fluid" />
              </div>
            </div>
          </div>
        </div>

      </div>

    </section>{/* /Promo Cards Section */}
    </div>
  )
}

export default PromoCards