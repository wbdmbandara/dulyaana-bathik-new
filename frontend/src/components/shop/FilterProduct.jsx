import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

// setPriceRange
// const setPriceRange = (min, max) => {
//   // Update the price range input values and slider positions
//   const minPriceInput = document.querySelector('.min-price-input');
//   const maxPriceInput = document.querySelector('.max-price-input');
//   const priceRangeSlider = document.querySelector('.price-range-slider');
//   const sliderProgress = document.querySelector('.slider-progress');

//   if (minPriceInput) minPriceInput.value = min;
//   if (maxPriceInput) maxPriceInput.value = max;
//   if (priceRangeSlider) {
//     priceRangeSlider.min = min;
//     priceRangeSlider.max = max;
//   }
//   const range = max - min;
//   sliderProgress.style.left = `${(min / 1000) * 100}%`;
//   sliderProgress.style.width = `${((max - min) / 1000) * 100}%`;
// };

function FilterProduct() {
	// get categories from api getParentCategories
  const currentURL = new URL(window.location.href);
	const [categories, setCategories] = useState([]);
	const [minPrice, setMinPrice] = useState(0);
	const [defaultMinPrice, setDefaultMinPrice] = useState(0);
	const [maxPrice, setMaxPrice] = useState(1000);
  const [defaultMaxPrice, setDefaultMaxPrice] = useState(1000);
  const [fabrics, setFabrics] = useState([]);
  const [selectedFabrics, setSelectedFabrics] = useState([]);

	useEffect(() => {
		const fetchCategories = async () => {
			try {
				const response = await fetch(`${API_URL}getParentCategories`, {
					method: "GET",
					headers: {
						"Content-Type": "application/json",
					},
				});
				const data = await response.json();
				if (data.success) {
					setCategories(data.categories);
				} else {
					console.error("Error fetching categories:", data.message);
				}
			} catch (error) {
				console.error("Error fetching categories:", error);
			}
		};
		fetchCategories();
	}, []);

	// get min and max price from api getMinAndMaxPrices
	useEffect(() => {
		const fetchMinAndMaxPrices = async () => {
			try {
				const response = await fetch(`${API_URL}getMinAndMaxPrices`, {
					method: "GET",
					headers: {
						"Content-Type": "application/json",
					},
				});
				const data = await response.json();
				if (data.success) {
					const apiMin = Math.floor(data.min_price);
					const apiMax = Math.ceil(data.max_price);

					setDefaultMinPrice(apiMin);
					setDefaultMaxPrice(apiMax);

					// Read values from URL params (if provided)
					const urlParams = new URLSearchParams(
						window.location.search
					);
					const urlMinRaw = urlParams.get("min_price");
					const urlMaxRaw = urlParams.get("max_price");

					const urlMin =
						urlMinRaw !== null && !isNaN(Number(urlMinRaw))
							? Number(urlMinRaw)
							: null;
					const urlMax =
						urlMaxRaw !== null && !isNaN(Number(urlMaxRaw))
							? Number(urlMaxRaw)
							: null;

					// If URL params provided, clamp them to API bounds
					let initialMin = apiMin;
					let initialMax = apiMax;

					if (urlMin !== null) {
						initialMin = Math.max(
							apiMin,
							Math.min(apiMax, Math.floor(urlMin))
						);
					}

					if (urlMax !== null) {
						initialMax = Math.max(
							apiMin,
							Math.min(apiMax, Math.ceil(urlMax))
						);
					}

					// Ensure min is not greater than max
					if (initialMin > initialMax) {
						// If both came from URL, prioritize sensible ordering: set min = max
						initialMin = initialMax;
					}

					setMinPrice(initialMin);
					setMaxPrice(initialMax);
				} else {
					console.error(
						"Error fetching min and max prices:",
						data.message
					);
				}
			} catch (error) {
				console.error("Error fetching min and max prices:", error);
			}
		};
		fetchMinAndMaxPrices();
	}, []);

  // get fabrics from api getFabricList
  useEffect(() => {
    const fetchFabrics = async () => {
      try {
        const response = await fetch(`${API_URL}getFabricList`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
			},
		});
        const data = await response.json();
        if (data.success) {
          setFabrics(data.fabrics);
        } else {
          console.error("Error fetching fabrics:", data.message);
        }
      } catch (error) {
        console.error("Error fetching fabrics:", error);
      }
    };
    fetchFabrics();
  }, []);

  const clearFabricFilters = () => {
    const fabricCheckboxes = document.querySelectorAll(
      ".fabric-filter input[type='checkbox']"
    );
    fabricCheckboxes.forEach((checkbox) => {
      checkbox.checked = false;
    });
    applyFabricFilters();
  };

  const applyFabricFilters = () => {
    const selectedFabrics = [];
    const fabricCheckboxes = document.querySelectorAll(
      ".fabric-filter input[type='checkbox']:checked"
    );
    fabricCheckboxes.forEach((checkbox) => {
      selectedFabrics.push(checkbox.value);
    });
    setSelectedFabrics(selectedFabrics);
    const currentURL = new URL(window.location.href);
    const urlParams = new URLSearchParams(currentURL.search);
    if (selectedFabrics.length > 0) {
      urlParams.set("fabrics", selectedFabrics.join(","));
    } else {
      urlParams.delete("fabrics");
    }
    window.location.href = `${currentURL.pathname}?${urlParams.toString()}`;
  };

  // set selectedFabrics using URL params on initial load
  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const fabricsParam = urlParams.get("fabrics");
    if (fabricsParam) {
      const fabricsFromURL = fabricsParam.split(",");
      setSelectedFabrics(fabricsFromURL);
    }
  }, []);

	return (
		<div className="col-lg-4 sidebar sticky-sidebar">
			{/* Mobile-only filter toggle: visible on small screens, toggles the filter panel */}
			<div className="mobile-filter-toggle d-lg-none mt-3">
				<button
					className="btn filter-collapse-btn"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#filterSidebarContent"
					aria-expanded="false"
					aria-controls="filterSidebarContent"
				>
					<i className="bi bi-filter me-2"></i>
					Filters
				</button>
			</div>

			<div
				id="filterSidebarContent"
				className="collapse d-lg-block widgets-container"
			>
				{/* Product Categories Widget */}
				<div className="product-categories-widget widget-item">
					<h3 className="widget-title">Categories</h3>

					<ul className="category-tree list-unstyled mb-0">
						{categories.map((category) => {
							const currentCategory = new URLSearchParams(
								window.location.search
							).get("category");
							return (
								<li className="category-item" key={category.id}>
									<div className="d-flex justify-content-between align-items-center category-header">
										<a
											href={`${
												window.location.pathname
											}?${new URLSearchParams({
												...Object.fromEntries(
													new URLSearchParams(
														window.location.search
													)
												),
												category: category.cat_slug,
											}).toString()}`}
											className={`category-link ${
												currentCategory ===
												category.cat_slug
													? "active"
													: ""
											}`}
										>
											{category.cat_name}
										</a>
									</div>
								</li>
							);
						})}
					</ul>
				</div>

				{/* Pricing Range Widget */}
				<div className="pricing-range-widget widget-item">
					<h3 className="widget-title">Price Range</h3>

					<div className="price-range-container">
						<div className="current-range mb-3 d-none">
							<span className="min-price">$0</span>
							<span className="max-price float-end">$500</span>
						</div>

						<div className="range-slider d-none">
							<div className="slider-track"></div>
							<div
								className="slider-progress"
								style={{ left: "0%", width: "50%" }}
							></div>
							<input
								type="range"
								className="min-range"
								min="0"
								max="1000"
								value={minPrice}
								step="10"
								onChange={(e) =>
									setMinPrice(Number(e.target.value))
								}
							/>
							<input
								type="range"
								className="max-range"
								min="0"
								max="1000"
								value={maxPrice}
								step="10"
								onChange={(e) =>
									setMaxPrice(Number(e.target.value))
								}
							/>
						</div>

						<div className="price-inputs mt-3">
							<div className="row g-2">
								<div className="col-6">
									<div className="input-group input-group-sm">
										<span className="input-group-text">
											Rs.
										</span>
										<input
											type="number"
											className="form-control min-price-input"
											placeholder="Min"
											min={defaultMinPrice}
											max={defaultMaxPrice}
											value={minPrice}
											step="100"
											onChange={(e) =>
												setMinPrice(
													Number(e.target.value)
												)
											}
										/>
									</div>
								</div>
								<div className="col-6">
									<div className="input-group input-group-sm">
										<span className="input-group-text">
											Rs.
										</span>
										<input
											type="number"
											className="form-control max-price-input"
											placeholder="Max"
											min={defaultMinPrice}
											max={defaultMaxPrice}
											value={maxPrice}
											step="100"
											onChange={(e) =>
												setMaxPrice(
													Number(e.target.value)
												)
											}
										/>
									</div>
								</div>
							</div>
						</div>

						<div className="filter-actions mt-3">
							<a
								href={`${
									currentURL.pathname
								}?${new URLSearchParams({
									...Object.fromEntries(
										new URLSearchParams(currentURL.search)
									),
									min_price: minPrice,
									max_price: maxPrice,
								}).toString()}`}
								className="btn btn-sm btn-primary w-100"
							>
								Apply Filter
							</a>
						</div>
					</div>
				</div>
				{/*/Pricing Range Widget */}

				{/* Brand Filter Widget */}
				<h3 className="brand-filter-widget widget-item d-none">
					Filter by Brand
				</h3>
				{/*/Brand Filter Widget */}

				{/* Color Filter Widget */}
				<div className="color-filter-widget widget-item d-none">
					<h3 className="widget-title">Filter by Color</h3>

					<div className="color-filter-content">
						<div className="color-options">
							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="black"
									id="color-black"
								/>
								<label
									className="form-check-label"
									htmlFor="color-black"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#000000" }}
										title="Black"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="white"
									id="color-white"
								/>
								<label
									className="form-check-label"
									htmlFor="color-white"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#ffffff" }}
										title="White"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="red"
									id="color-red"
								/>
								<label
									className="form-check-label"
									htmlFor="color-red"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#e74c3c" }}
										title="Red"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="blue"
									id="color-blue"
								/>
								<label
									className="form-check-label"
									htmlFor="color-blue"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#3498db" }}
										title="Blue"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="green"
									id="color-green"
								/>
								<label
									className="form-check-label"
									htmlFor="color-green"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#2ecc71" }}
										title="Green"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="yellow"
									id="color-yellow"
								/>
								<label
									className="form-check-label"
									htmlFor="color-yellow"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#f1c40f" }}
										title="Yellow"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="purple"
									id="color-purple"
								/>
								<label
									className="form-check-label"
									htmlFor="color-purple"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#9b59b6" }}
										title="Purple"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="orange"
									id="color-orange"
								/>
								<label
									className="form-check-label"
									htmlFor="color-orange"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#FFA500" }}
										title="Orange"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="orange"
									id="color-orange"
								/>
								<label
									className="form-check-label"
									htmlFor="color-orange"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#e67e22" }}
										title="Orange"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="orange"
									id="color-orange"
								/>
								<label
									className="form-check-label"
									htmlFor="color-orange"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#e67e22" }}
										title="Orange"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="orange"
									id="color-orange"
								/>
								<label
									className="form-check-label"
									htmlFor="color-orange"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#e67e22" }}
										title="Orange"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="orange"
									id="color-orange"
								/>
								<label
									className="form-check-label"
									htmlFor="color-orange"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#e67e22" }}
										title="Orange"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="pink"
									id="color-pink"
								/>
								<label
									className="form-check-label"
									htmlFor="color-pink"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#fd79a8" }}
										title="Pink"
									></span>
								</label>
							</div>

							<div className="form-check color-option">
								<input
									className="form-check-input"
									type="checkbox"
									value="brown"
									id="color-brown"
								/>
								<label
									className="form-check-label"
									htmlFor="color-brown"
								>
									<span
										className="color-swatch"
										style={{ backgroundColor: "#795548" }}
										title="Brown"
									></span>
								</label>
							</div>
						</div>

						<div className="filter-actions mt-3">
							<button
								type="button"
								className="btn btn-sm btn-outline-secondary"
							>
								Clear All
							</button>
							<button
								type="button"
								className="btn btn-sm btn-primary"
							>
								Apply Filter
							</button>
						</div>
					</div>
				</div>
				{/*/Color Filter Widget */}

				{/* Fabric Filter Widget */}
				<div className="brand-filter-widget widget-item">
					<h3 className="widget-title">Filter by Fabric</h3>

					<div className="brand-filter-content">
						<div className="brand-search">
							<input
								type="text"
								className="form-control"
								placeholder="Search fabrics..."
								spellCheck="false"
								data-ms-editor="true"
                onChange={(e) => {
                  const searchTerm = e.target.value.toLowerCase();
                  const fabricItems = document.querySelectorAll('.brand-item');
                  fabricItems.forEach(item => {
                    const label = item.querySelector('label').innerText.toLowerCase();
                    if (label.includes(searchTerm)) {
                      item.style.display = '';
                    } else {
                      item.style.display = 'none';
                    }
                  });
                }}
							/>
							<i className="bi bi-search"></i>
						</div>

						<div className="brand-list">
							{fabrics.map((fabric, index) => (
								<div className="brand-item" key={index}>
									<div className="form-check fabric-filter">
										<input
											className="form-check-input"
											type="checkbox"
											id={`fabric-${index}`}
                      value={fabric.fabric}
                      defaultChecked={selectedFabrics.includes(fabric.fabric)}
										/>
										<label
											className="form-check-label"
											htmlFor={`fabric-${index}`}
										>
											{fabric.fabric}
											<span className="brand-count">
												({fabric.item_count})
											</span>
										</label>
									</div>
								</div>
							))}
						</div>

						<div className="brand-actions">
							<button className="btn btn-sm btn-outline-primary" onClick={applyFabricFilters}>
								Apply Filter
							</button>
							<button
								className="btn btn-sm btn-link"
								onClick={clearFabricFilters}
							>
								Clear All
							</button>
						</div>
					</div>
				</div>
				{/*/Brand Filter Widget */}
			</div>
		</div>
	);
}

export default FilterProduct;
