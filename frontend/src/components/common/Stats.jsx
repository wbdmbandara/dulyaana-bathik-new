import React, { useEffect } from 'react'

function Stats() {
  useEffect(() => {
    const initializePureCounter = async () => {
      const { default: PureCounter } = await import('@srexi/purecounterjs');
      new PureCounter();
    };
    initializePureCounter();
  }, []);

  return (
    <section id="stats" className="stats section light-background">

      <div className="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

        <div className="row gy-4">

          <div className="col-lg-3 col-md-6">
            <div className="stats-item">
              <i className="bi bi-emoji-smile"></i>
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="0" className="purecounter">232</span>
              <p><strong>Happy Clients</strong> <span>consequuntur quae</span></p>
            </div>
          </div>
          {/* End Stats Item */}

          <div className="col-lg-3 col-md-6">
            <div className="stats-item">
              <i className="bi bi-journal-richtext"></i>
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="0" className="purecounter">521</span>
              <p><strong>Projects</strong> <span>adipisci atque cum quia aut</span></p>
            </div>
          </div>
          {/* End Stats Item */}

          <div className="col-lg-3 col-md-6">
            <div className="stats-item">
              <i className="bi bi-headset"></i>
              <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="0" className="purecounter">1453</span>
              <p><strong>Hours Of Support</strong> <span>aut commodi quaerat</span></p>
            </div>
          </div>
          {/* End Stats Item */}

          <div className="col-lg-3 col-md-6">
            <div className="stats-item">
              <i className="bi bi-people"></i>
              <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="0" className="purecounter">32</span>
              <p><strong>Hard Workers</strong> <span>rerum asperiores dolor</span></p>
            </div>
          </div>
          {/* End Stats Item */}

        </div>

      </div>

    </section>
  )
}

export default Stats