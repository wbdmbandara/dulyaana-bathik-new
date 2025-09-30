import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

function PromoCards() {
  const [promoCards, setPromoCards] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchPromoCards();
  }, []);

  const fetchPromoCards = async () => {
    try {
      setLoading(true);
      const response = await fetch(`${BACKEND_URL}api/getPromoCards`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
        if (data.success) {
        setPromoCards(data.promoCards || []);
      } else {
        throw new Error(data.message || 'Failed to fetch promo cards');
      }
    } catch (err) {
      console.error('Error fetching promo cards:', err);
      setError(err.message);
      // Fallback to default cards if API fails
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div>
        <section id="promo-cards" className="promo-cards section">
          <div className="container" data-aos="fade-up" data-aos-delay="100">
            <div className="row g-4">
              <div className="col-12 text-center">
                <div className="spinner-border text-primary" role="status">
                  <span className="visually-hidden">Loading promo cards...</span>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    );
  }

  return (
    <div>
        {/* Promo Cards Section */}
    <section id="promo-cards" className="promo-cards section">

      <div className="container" data-aos="fade-up" data-aos-delay="100">

        <div className="row g-4">
          {promoCards.map((card, index) => (
            <div 
              key={card.id || index} 
              className="col-md-6 col-lg-3" 
              data-aos="fade-up" 
              data-aos-delay={100 + (index * 100)}
            >              <div className={`promo-card ${card.card_class || `card-${(index % 4) + 1}`}`}>
                <div className="promo-content">
                  <p className="small-text">{card.small_text}</p>
                  <h3 className="promo-title">{card.promo_title}</h3>
                  <p className="promo-description">{card.promo_description}</p>
                  <a href={card.promo_link || "#"} className="btn-shop">
                    {card.button_text || "Shop Now"}
                  </a>
                </div>
                <div className="promo-image">
                  <img 
                    src={card.promo_image ? `${BACKEND_URL}${card.promo_image}` : ""} 
                    alt={card.promo_title || "Product"} 
                    className="img-fluid"
                    // onError={(e) => {
                    //   e.target.src = "";
                    // }}
                  />
                </div>
              </div>
            </div>
          ))}
        </div>

        {error && (
          <div className="row mt-3">
            <div className="col-12">
              <div className="alert alert-warning text-center" role="alert">
                <small>Promo cards could not be loaded. Please try again later.</small>
              </div>
            </div>
          </div>
        )}

      </div>

    </section>{/* /Promo Cards Section */}
    </div>
  )
}

export default PromoCards