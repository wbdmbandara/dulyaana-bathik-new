import React from 'react'

function AboutCompany() {
  return (
    <section id="about-2" className="about-2 section">

      <div className="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

        <div className="row mb-lg-5">
          <span className="text-uppercase small-title mb-2">About Our Company</span>
          <div className="col-lg-6">
            <h2 className="about-title">Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</h2>
          </div>
          <div className="col-lg-6 description-wrapper">
            <p className="about-description">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
          </div>
        </div>

        <div className="row g-4">

          <div className="col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
            <div className="content-card">
              <div className="card-image">
                <img src="assets/img/about/about-portrait-16.webp" alt="" className="img-fluid" />
              </div>
              <div className="card-content">
                <h3>Ut enim ad minima veniam</h3>
                <p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.</p>
                <a href="#" className="read-more">
                  Explore More <i className="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
          {/* End Content Card */}

          <div className="col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
            <div className="content-card">
              <div className="card-image">
                <img src="assets/img/about/about-portrait-4.webp" alt="" className="img-fluid" />
              </div>
              <div className="card-content">
                <h3>Quis autem vel eum iure</h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum.</p>
                <a href="#" className="read-more">
                  Learn More <i className="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
          {/* End Content Card */}

          <div className="col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
            <div className="content-card">
              <div className="card-image">
                <img src="assets/img/about/about-portrait-1.webp" alt="" className="img-fluid" />
              </div>
              <div className="card-content">
                <h3>Nam libero tempore</h3>
                <p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates.</p>
                <a href="#" className="read-more">
                  Discover More <i className="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
          {/* End Content Card */}

        </div>

      </div>

    </section>
  )
}

export default AboutCompany