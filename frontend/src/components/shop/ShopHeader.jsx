import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

function ShopHeader() {
  const [activeFilters, setActiveFilters] = useState([])
  const [searchQuery, setSearchQuery] = useState(
    new URLSearchParams(window.location.search).get("search") || ""
  )
  const [sortOption, setSortOption] = useState("")
  const [itemsPerPage, setItemsPerPage] = useState(12)
  
  // set active filters based on url parameters
  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const filters = {
      category: urlParams.get("category")?.replace("-", " ") || "",
      fabrics: urlParams.get("fabrics") ? urlParams.get("fabrics").split(",") : []
    };
    setActiveFilters(filters);
  }, []);

  const searchOnKeyUp = (event) => {
    if (event.key === "Enter") {
      applySearch();
    } else {
      setSearchQuery(event.target.value);
    }
  };

  const applySearch = () => {
    const currentURL = new URL(window.location.href);
    const urlParams = new URLSearchParams(currentURL.search);
    if (searchQuery) {
      urlParams.set("search", searchQuery);
    } else {
      urlParams.delete("search");
    }
    window.location.href = `${currentURL.pathname}?${urlParams.toString()}`;
  };

  return (
    <section id="category-header" className="category-header section">

            <div className="container aos-init aos-animate" data-aos="fade-up">

              {/* Filter and Sort Options */}
              <div className="filter-container mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                <div className="row g-3">
                  <div className="col-12 col-md-7 col-lg-8">
                    <div className="filter-item search-form">
                      <label htmlFor="productSearch" className="form-label">Search Products</label>
                      <div className="input-group">
                        <input type="text" className="form-control" id="productSearch" placeholder="Search for products..." aria-label="Search for products" spellCheck="false" data-ms-editor="true" onChange={(e) => setSearchQuery(e.target.value)} onKeyUp={searchOnKeyUp} value={searchQuery}/>
                        <button className="btn search-btn" type="button" onClick={applySearch}>
                          <i className="bi bi-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div className="col-12 col-md-6 col-lg-2 d-none">
                    <div className="filter-item">
                      <label htmlFor="priceRange" className="form-label">Price Range</label>
                      <select className="form-select" id="priceRange">
                        <option defaultValue={""}>All Prices</option>
                        <option>Under $25</option>
                        <option>$25 to $50</option>
                        <option>$50 to $100</option>
                        <option>$100 to $200</option>
                        <option>$200 &amp; Above</option>
                      </select>
                    </div>
                  </div>

                  <div className="col-6 col-md-3 col-lg-2">
                    <div className="filter-item">
                      <label htmlFor="sortBy" className="form-label">Sort By</label>
                      <select className="form-select" id="sortBy">
                        <option defaultValue={""}>Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Customer Rating</option>
                        <option>Newest Arrivals</option>
                      </select>
                    </div>
                  </div>

                  <div className="col-6 col-md-2 col-lg-2">
                    <div className="filter-item">
                      <label htmlFor="itemsPerPage" className="form-label">Sarees/Page</label>
                      <div className="d-flex align-items-center">
                        <div className="items-per-page">
                          <select className="form-select" id="itemsPerPage" aria-label="Items per page">
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="48">48</option>
                            <option value="96">96</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="row mt-3">
                  <div className="col-12 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <div className="active-filters">
                      <span className="active-filter-label">Active Filters:</span>
                      <div className="filter-tags">
                        {!activeFilters.category && activeFilters.fabrics?.length === 0 && (
                          <span className="filter-tag">No filters applied</span>
                        )}
                        {activeFilters.category && (
                          <span className="filter-tag text-capitalize">
                            {activeFilters.category}
                          </span>
                        )}
                        {activeFilters.fabrics && (
                          activeFilters.fabrics.map((fabric) => (
                            <span key={fabric} className="filter-tag text-capitalize">
                              {fabric}
                            </span>
                          ))
                        )}
                        <a className="clear-all-btn" href="/shop">Clear All</a>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

            </div>

          </section>
  )
}

export default ShopHeader