import React, { useEffect } from 'react'

function Testimonials() {
  useEffect(() => {
    // Initialize Swiper when component mounts
    if (window.Swiper && document.querySelector('.testimonials-slider')) {
      const swiperConfig = {
        slidesPerView: 1,
        loop: true,
        speed: 600,
        autoplay: {
          delay: 5000
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev"
        }
      };
      
      new window.Swiper('.testimonials-slider', swiperConfig);
    }
  }, []);
  return (
    <section id="testimonials" className="testimonials section">

      <div className="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">        <div className="testimonials-slider swiper init-swiper">
          <div className="swiper-wrapper">

            <div className="swiper-slide" role="group" aria-label="1 / 4">
              <div className="testimonial-item">
                <div className="row">
                  <div className="col-lg-8">
                    <h2>Sed ut perspiciatis unde omnis</h2>
                    <p>
                      Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.
                    </p>
                    <p>
                      Beatae magnam dolore quia ipsum. Voluptatem totam et qui dolore dignissimos. Amet quia sapiente laudantium nihil illo et assumenda sit cupiditate. Nam perspiciatis perferendis minus consequatur. Enim ut eos quo.
                    </p>
                    <div className="profile d-flex align-items-center">
                      <img src="assets/img/person/person-m-7.webp" className="profile-img" alt="" />
                      <div className="profile-info">
                        <h3>Saul Goodman</h3>
                        <span>Client</span>
                      </div>
                    </div>
                  </div>
                  <div className="col-lg-4 d-none d-lg-block">
                    <div className="featured-img-wrapper">
                      <img src="assets/img/person/person-m-7.webp" className="featured-img" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* End Testimonial Item */}

            <div className="swiper-slide" role="group" aria-label="2 / 4">
              <div className="testimonial-item">
                <div className="row">
                  <div className="col-lg-8">
                    <h2>Nemo enim ipsam voluptatem</h2>
                    <p>
                      Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
                    </p>
                    <p>
                      Dolorem excepturi esse qui amet maxime quibusdam aut repellendus voluptatum. Corrupti enim a repellat cumque est laborum fuga consequuntur. Dolorem nostrum deleniti quas voluptatem iure dolorum rerum. Repudiandae doloribus ut repellat harum vero aut. Modi aut velit aperiam aspernatur odit ut vitae.
                    </p>
                    <div className="profile d-flex align-items-center">
                      <img src="assets/img/person/person-f-8.webp" className="profile-img" alt="" />
                      <div className="profile-info">
                        <h3>Sara Wilsson</h3>
                        <span>Designer</span>
                      </div>
                    </div>
                  </div>
                  <div className="col-lg-4 d-none d-lg-block">
                    <div className="featured-img-wrapper">
                      <img src="assets/img/person/person-f-8.webp" className="featured-img" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* End Testimonial Item */}            <div className="swiper-slide" role="group" aria-label="3 / 4">
              <div className="testimonial-item">
                <div className="row">
                  <div className="col-lg-8">
                    <h2>
                      Labore nostrum eos impedit
                    </h2>
                    <p>
                      Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
                    </p>
                    <p>
                      Itaque ut explicabo vero occaecati est quam rerum sed. Numquam tempora aut aut quaerat quia illum. Nobis quia autem odit ipsam numquam. Doloribus sit sint corporis eius totam fuga. Hic nostrum suscipit corrupti nam expedita adipisci aut optio.
                    </p>
                    <div className="profile d-flex align-items-center">
                      <img src="assets/img/person/person-m-9.webp" className="profile-img" alt="" />
                      <div className="profile-info">
                        <h3>Matt Brandon</h3>
                        <span>Freelancer</span>
                      </div>
                    </div>
                  </div>
                  <div className="col-lg-4 d-none d-lg-block">
                    <div className="featured-img-wrapper">
                      <img src="assets/img/person/person-m-9.webp" className="featured-img" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* End Testimonial Item */}            <div className="swiper-slide" role="group" aria-label="4 / 4">
              <div className="testimonial-item">
                <div className="row">
                  <div className="col-lg-8">
                    <h2>Impedit dolor facilis nulla</h2>
                    <p>
                      Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                    </p>
                    <p>
                      Omnis aspernatur accusantium qui delectus praesentium repellendus. Facilis sint odio aspernatur voluptas commodi qui qui qui pariatur. Corrupti deleniti itaque quaerat ipsum deleniti culpa tempora tempore. Et consequatur exercitationem hic aspernatur nobis est voluptatibus architecto laborum.
                    </p>
                    <div className="profile d-flex align-items-center">
                      <img src="assets/img/person/person-f-10.webp" className="profile-img" alt="" />
                      <div className="profile-info">
                        <h3>Jena Karlis</h3>
                        <span>Store Owner</span>
                      </div>
                    </div>
                  </div>
                  <div className="col-lg-4 d-none d-lg-block">
                    <div className="featured-img-wrapper">
                      <img src="assets/img/person/person-f-10.webp" className="featured-img" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* End Testimonial Item */}          </div>          <div className="swiper-navigation w-100 d-flex align-items-center justify-content-center">
            <div className="swiper-button-prev" tabIndex="0" role="button" aria-label="Previous slide"></div>
            <div className="swiper-button-next" tabIndex="0" role="button" aria-label="Next slide"></div>
          </div>
        </div>

      </div>

    </section>
  )
}

export default Testimonials