import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function Profile(){
    const [user, setUser] = useState(null);
    const [formErrors, setFormErrors] = useState({});
    const [successMsg, setSuccessMsg] = useState({});
    const navigate = useNavigate();
    const [activeTab, setActiveTab] = useState(window.location.search.split('?')[1] || 'profile');

    useEffect(() => {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            setActiveTab(window.location.search.split('?')[1] || 'profile');
            setUser(JSON.parse(storedUser));
        }else{
            navigate('/login');
        }
    }, []);

    if (!user) {
        return <div>Loading...</div>;
    }

    const updateCustomerInfo = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch(`${API_URL}customers/${user.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(user)
            });
            if (!response.ok) {
                setFormErrors({ submit: 'Failed to update your information' });
                throw new Error('Failed to update your information');
            }
            const updatedUser = await response.json();
            setUser(updatedUser.customer);
            setFormErrors({});
            setSuccessMsg({ submit: 'Your information updated successfully' });
            localStorage.setItem('user', JSON.stringify(updatedUser.customer));
        } catch (error) {
            setUser(user);
            setFormErrors({ submit: error.message });
            console.error(error);
        }
    };

    return (
        <section id="account" className="account section">

            <div className="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

                {/* Mobile Sidebar Toggle Button */}
                <div className="sidebar-toggle d-lg-none mb-3">
                <button className="btn btn-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#profileSidebar" aria-expanded="false" aria-controls="profileSidebar">
                    <i className="bi bi-list me-2"></i> Profile Menu
                </button>
                </div>

                <div className="row">
                {/* Profile Sidebar */}
                <div className="col-lg-3 profile-sidebar collapse d-lg-block aos-init aos-animate" id="profileSidebar" data-aos="fade-right" data-aos-delay="200">
                    <div className="profile-header">
                    <div className="profile-avatar">
                        <span>{typeof user?.name === 'string' && user.name.charAt(0) ? user.name.charAt(0) : <i className="bi bi-person-circle"></i>}</span>
                    </div>
                    <div className="profile-info">
                        <h4>{user?.name}</h4>
                        <div className="profile-bonus d-none">
                        <i className="bi bi-gift"></i>
                        <span>100 bonuses available</span>
                        </div>
                    </div>
                    </div>

                    <div className="profile-nav">
                    <ul className="nav flex-column" id="profileTabs" role="tablist">
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'profile' ? 'nav-link active' : 'nav-link'} id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="false" tabIndex="-1">
                            <i className="bi bi-person"></i>
                            <span>Personal info</span>
                        </button>
                        </li>
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'orders' ? 'nav-link active' : 'nav-link'} id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="true">
                            <i className="bi bi-box-seam"></i>
                            <span>Orders</span>
                            <span className="badge">1</span>
                        </button>
                        </li>
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'wishlist' ? 'nav-link active' : 'nav-link'} id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab" aria-controls="wishlist" aria-selected="false" tabIndex="-1">
                            <i className="bi bi-heart"></i>
                            <span>Wishlist</span>
                        </button>
                        </li>
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'payment' ? 'nav-link active' : 'nav-link'} id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false" tabIndex="-1">
                            <i className="bi bi-credit-card"></i>
                            <span>Payment methods</span>
                        </button>
                        </li>
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'reviews' ? 'nav-link active' : 'nav-link'} id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false" tabIndex="-1">
                            <i className="bi bi-star"></i>
                            <span>My reviews</span>
                        </button>
                        </li>
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'addresses' ? 'nav-link active' : 'nav-link'} id="addresses-tab" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab" aria-controls="addresses" aria-selected="false" tabIndex="-1">
                            <i className="bi bi-geo-alt"></i>
                            <span>Addresses</span>
                        </button>
                        </li>
                        <li className="nav-item" role="presentation">
                        <button className={activeTab === 'notifications' ? 'nav-link active' : 'nav-link'} id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false" tabIndex="-1">
                            <i className="bi bi-bell"></i>
                            <span>Notifications</span>
                        </button>
                        </li>
                    </ul>

                    <h6 className="nav-section-title">Customer service</h6>
                    <ul className="nav flex-column">
                        <li className="nav-item">
                        <a href="#" className="nav-link">
                            <i className="bi bi-question-circle"></i>
                            <span>Help center</span>
                        </a>
                        </li>
                        <li className="nav-item">
                        <a href="#" className="nav-link">
                            <i className="bi bi-file-text"></i>
                            <span>Terms and conditions</span>
                        </a>
                        </li>
                        <li className="nav-item">
                        <a href="/logout" className="nav-link logout">
                            <i className="bi bi-box-arrow-right"></i>
                            <span>Log out</span>
                        </a>
                        </li>
                    </ul>
                    </div>
                </div>

                {/* Profile Content */}
                <div className="col-lg-9 profile-content aos-init aos-animate" data-aos="fade-left" data-aos-delay="300">
                    <div className="tab-content" id="profileTabsContent">
                    
                    {/* Personal Info Tab */}
                    <div className={activeTab === 'profile' ? 'tab-pane fade active show' : 'tab-pane fade'} id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div className="tab-header">
                        <h2>Personal Information</h2>
                        </div>
                        <div className="personal-info-form aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <form className="php-email-form" onSubmit={updateCustomerInfo}>
                            {formErrors.submit && <div className="alert alert-danger">{formErrors.submit}</div>}
                            {successMsg.submit && <div className="alert alert-success">{successMsg.submit}</div>}
                            <div className="row">
                                <div className="col-md-8 mb-3">
                                    <label htmlFor="name" className="form-label">Name</label>
                                    <input type="text" className="form-control" id="name" name="name" value={user.name} onChange={(e) => setUser({ ...user, name: e.target.value })} required="" />
                                </div>
                                <div className="col-md-4 mb-3">
                                    <label htmlFor="birthday" className="form-label">Date of Birth</label>
                                    <input type="date" className="form-control" id="birthday" name="birthday" value={user.birthday} onChange={(e) => setUser({ ...user, birthday: e.target.value })} />
                                </div>
                            </div>
                            <div className="row">
                            <div className="col-md-6 mb-3">
                                <label htmlFor="email" className="form-label">Email</label>
                                <input type="email" className="form-control" id="email" name="email" readOnly value={user.email} onChange={(e) => setUser({ ...user, email: e.target.value })} required="" />
                            </div>
                            <div className="col-md-6 mb-3">
                                <label htmlFor="phone" className="form-label">Phone</label>
                                <input type="tel" className="form-control" id="phone" name="phone" value={user.phone} onChange={(e) => setUser({ ...user, phone: e.target.value })} />
                            </div>
                            </div>
                            <div className="mb-3">
                            <label className="form-label d-block">Gender</label>
                            <div className="form-check form-check-inline">
                                <input className="form-check-input" type="radio" name="gender" id="genderMale" value="male" checked={user?.gender === "male"} onChange={(e) => setUser({ ...user, gender: e.target.value })} />
                                <label className="form-check-label" htmlFor="genderMale">Male</label>
                            </div>
                            <div className="form-check form-check-inline">
                                <input className="form-check-input" type="radio" name="gender" id="genderFemale" value="female" checked={user?.gender === "female"} onChange={(e) => setUser({ ...user, gender: e.target.value })} />
                                <label className="form-check-label" htmlFor="genderFemale">Female</label>
                            </div>
                            </div>
                            <div className="loading">Loading</div>
                            <div className="error-message"></div>
                            <div className="sent-message">Your information has been updated. Thank you!</div>
                            <div className="text-end">
                            <button type="submit" className="btn btn-save">Save Changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    
                    {/* Orders Tab */}
                    <div className={activeTab === "orders" ? "tab-pane fade show active" : "tab-pane fade"} id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div className="tab-header">
                        <h2>Orders</h2>
                        <div className="tab-filters">
                            <div className="row">
                            <div className="col-md-6 mb-3 mb-md-0">
                                <div className="dropdown">
                                <button className="btn dropdown-toggle" type="button" id="statusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Select status</span>
                                    <i className="bi bi-chevron-down"></i>
                                </button>
                                <ul className="dropdown-menu" aria-labelledby="statusFilter">
                                    <li><a className="dropdown-item" href="#">All statuses</a></li>
                                    <li><a className="dropdown-item" href="#">In progress</a></li>
                                    <li><a className="dropdown-item" href="#">Delivered</a></li>
                                    <li><a className="dropdown-item" href="#">Canceled</a></li>
                                </ul>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <div className="dropdown">
                                <button className="btn dropdown-toggle" type="button" id="timeFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>For all time</span>
                                    <i className="bi bi-chevron-down"></i>
                                </button>
                                <ul className="dropdown-menu" aria-labelledby="timeFilter">
                                    <li><a className="dropdown-item" href="#">For all time</a></li>
                                    <li><a className="dropdown-item" href="#">Last 30 days</a></li>
                                    <li><a className="dropdown-item" href="#">Last 6 months</a></li>
                                    <li><a className="dropdown-item" href="#">Last year</a></li>
                                </ul>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                        <div className="orders-table">
                        <div className="table-header">
                            <div className="row">
                            <div className="col-md-3">
                                <div className="sort-header">
                                Order #
                                </div>
                            </div>
                            <div className="col-md-3">
                                <div className="sort-header">
                                Order date
                                <i className="bi bi-arrow-down-up"></i>
                                </div>
                            </div>
                            <div className="col-md-3">
                                <div className="sort-header">
                                Status
                                </div>
                            </div>
                            <div className="col-md-3">
                                <div className="sort-header">
                                Total
                                <i className="bi bi-arrow-down-up"></i>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div className="order-items">
                            {/* Order Item 1 */}
                            <div className="order-item">
                            <div className="row align-items-center">
                                <div className="col-md-3">
                                <div className="order-id">78A6431D409</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-date">02/15/2025</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-status in-progress">
                                    <span className="status-dot"></span>
                                    <span>In progress</span>
                                </div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-total">$2,105.90</div>
                                </div>
                            </div>
                            <div className="order-products">
                                <div className="product-thumbnails">
                                <img src="assets/img/product/product-1.webp" alt="Product" className="product-thumb" loading="lazy" />
                                <img src="assets/img/product/product-2.webp" alt="Product" className="product-thumb" loading="lazy" />
                                <img src="assets/img/product/product-3.webp" alt="Product" className="product-thumb" loading="lazy" />
                                </div>
                                <button type="button" className="order-details-link" data-bs-toggle="collapse" data-bs-target="#orderDetails1" aria-expanded="false" aria-controls="orderDetails1">
                                <i className="bi bi-chevron-down"></i>
                                </button>
                            </div>
                            <div className="collapse order-details" id="orderDetails1">
                                <div className="order-details-content">
                                <div className="order-details-header">
                                    <h5>Order Details</h5>
                                    <div className="order-info">
                                    <div className="info-item">
                                        <span className="info-label">Order Date:</span>
                                        <span className="info-value">02/15/2025</span>
                                    </div>
                                    <div className="info-item">
                                        <span className="info-label">Payment Method:</span>
                                        <span className="info-value">Credit Card (**** 4589)</span>
                                    </div>
                                    </div>
                                </div>
                                <div className="order-items-list">
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-1.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Lorem ipsum dolor sit amet</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-001</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$899.99</div>
                                    </div>
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-2.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Consectetur adipiscing elit</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-002</span>
                                        <span className="item-qty">Qty: 2</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$599.95</div>
                                    </div>
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-3.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Sed do eiusmod tempor</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-003</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$129.99</div>
                                    </div>
                                </div>
                                <div className="order-summary">
                                    <div className="summary-row">
                                    <span>Subtotal:</span>
                                    <span>$1,929.93</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Shipping:</span>
                                    <span>$15.99</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Tax:</span>
                                    <span>$159.98</span>
                                    </div>
                                    <div className="summary-row total">
                                    <span>Total:</span>
                                    <span>$2,105.90</span>
                                    </div>
                                </div>
                                <div className="shipping-info">
                                    <div className="shipping-address">
                                    <h6>Shipping Address</h6>
                                    <p>123 Main Street<br/>Apt 4B<br/>New York, NY 10001<br/>United States</p>
                                    </div>
                                    <div className="shipping-method">
                                    <h6>Shipping Method</h6>
                                    <p>Express Delivery (2-3 business days)</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                            {/* End Order Item */}

                            {/* Order Item 2 */}
                            <div className="order-item">
                            <div className="row align-items-center">
                                <div className="col-md-3">
                                <div className="order-id">47H76G09F33</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-date">12/10/2024</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-status delivered">
                                    <span className="status-dot"></span>
                                    <span>Delivered</span>
                                </div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-total">$360.75</div>
                                </div>
                            </div>
                            <div className="order-products">
                                <div className="product-thumbnails">
                                <img src="assets/img/product/product-4.webp" alt="Product" className="product-thumb" loading="lazy" />
                                </div>
                                <button type="button" className="order-details-link" data-bs-toggle="collapse" data-bs-target="#orderDetails2" aria-expanded="false" aria-controls="orderDetails2">
                                <i className="bi bi-chevron-down"></i>
                                </button>
                            </div>
                            <div className="collapse order-details" id="orderDetails2">
                                <div className="order-details-content">
                                <div className="order-details-header">
                                    <h5>Order Details</h5>
                                    <div className="order-info">
                                    <div className="info-item">
                                        <span className="info-label">Order Date:</span>
                                        <span className="info-value">12/10/2024</span>
                                    </div>
                                    <div className="info-item">
                                        <span className="info-label">Payment Method:</span>
                                        <span className="info-value">Credit Card (**** 7821)</span>
                                    </div>
                                    </div>
                                </div>
                                <div className="order-items-list">
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-4.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Ut enim ad minim veniam</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-004</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$329.99</div>
                                    </div>
                                </div>
                                <div className="order-summary">
                                    <div className="summary-row">
                                    <span>Subtotal:</span>
                                    <span>$329.99</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Shipping:</span>
                                    <span>$9.99</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Tax:</span>
                                    <span>$20.77</span>
                                    </div>
                                    <div className="summary-row total">
                                    <span>Total:</span>
                                    <span>$360.75</span>
                                    </div>
                                </div>
                                <div className="shipping-info">
                                    <div className="shipping-address">
                                    <h6>Shipping Address</h6>
                                    <p>123 Main Street<br/>Apt 4B<br/>New York, NY 10001<br/>United States</p>
                                    </div>
                                    <div className="shipping-method">
                                    <h6>Shipping Method</h6>
                                    <p>Standard Shipping (5-7 business days)</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                            {/* End Order Item */}

                            {/* Order Item 3 */}
                            <div className="order-item">
                            <div className="row align-items-center">
                                <div className="col-md-3">
                                <div className="order-id">502TR872W2</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-date">11/05/2024</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-status delivered">
                                    <span className="status-dot"></span>
                                    <span>Delivered</span>
                                </div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-total">$4,268.00</div>
                                </div>
                            </div>
                            <div className="order-products">
                                <div className="product-thumbnails">
                                <img src="assets/img/product/product-5.webp" alt="Product" className="product-thumb" loading="lazy" />
                                <img src="assets/img/product/product-6.webp" alt="Product" className="product-thumb" loading="lazy" />
                                <img src="assets/img/product/product-7.webp" alt="Product" className="product-thumb" loading="lazy" />
                                <span className="more-products">+3</span>
                                </div>
                                <button type="button" className="order-details-link" data-bs-toggle="collapse" data-bs-target="#orderDetails3" aria-expanded="false" aria-controls="orderDetails3">
                                <i className="bi bi-chevron-down"></i>
                                </button>
                            </div>
                            <div className="collapse order-details" id="orderDetails3">
                                <div className="order-details-content">
                                <div className="order-details-header">
                                    <h5>Order Details</h5>
                                    <div className="order-info">
                                    <div className="info-item">
                                        <span className="info-label">Order Date:</span>
                                        <span className="info-value">11/05/2024</span>
                                    </div>
                                    <div className="info-item">
                                        <span className="info-label">Payment Method:</span>
                                        <span className="info-value">Credit Card (**** 4589)</span>
                                    </div>
                                    </div>
                                </div>
                                <div className="order-items-list">
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-5.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Quis nostrud exercitation</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-005</span>
                                        <span className="item-qty">Qty: 2</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$1,299.99</div>
                                    </div>
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-6.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Ullamco laboris nisi</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-006</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$799.99</div>
                                    </div>
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-7.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Aliquip ex ea commodo</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-007</span>
                                        <span className="item-qty">Qty: 3</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$449.99</div>
                                    </div>
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-8.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Duis aute irure dolor</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-008</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$249.99</div>
                                    </div>
                                </div>
                                <div className="order-summary">
                                    <div className="summary-row">
                                    <span>Subtotal:</span>
                                    <span>$3,899.94</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Shipping:</span>
                                    <span>$29.99</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Tax:</span>
                                    <span>$338.07</span>
                                    </div>
                                    <div className="summary-row total">
                                    <span>Total:</span>
                                    <span>$4,268.00</span>
                                    </div>
                                </div>
                                <div className="shipping-info">
                                    <div className="shipping-address">
                                    <h6>Shipping Address</h6>
                                    <p>456 Business Ave<br/>Suite 200<br/>San Francisco, CA 94107<br/>United States</p>
                                    </div>
                                    <div className="shipping-method">
                                    <h6>Shipping Method</h6>
                                    <p>Premium Delivery (1-2 business days)</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                            {/* End Order Item */}

                            {/* Order Item 4 */}
                            <div className="order-item">
                            <div className="row align-items-center">
                                <div className="col-md-3">
                                <div className="order-id">34VB5540K83</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-date">09/22/2024</div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-status canceled">
                                    <span className="status-dot"></span>
                                    <span>Canceled</span>
                                </div>
                                </div>
                                <div className="col-md-3">
                                <div className="order-total">$987.50</div>
                                </div>
                            </div>
                            <div className="order-products">
                                <div className="product-thumbnails">
                                <img src="assets/img/product/product-8.webp" alt="Product" className="product-thumb" loading="lazy" />
                                <img src="assets/img/product/product-9.webp" alt="Product" className="product-thumb" loading="lazy" />
                                </div>
                                <button type="button" className="order-details-link" data-bs-toggle="collapse" data-bs-target="#orderDetails4" aria-expanded="false" aria-controls="orderDetails4">
                                <i className="bi bi-chevron-down"></i>
                                </button>
                            </div>
                            <div className="collapse order-details" id="orderDetails4">
                                <div className="order-details-content">
                                <div className="order-details-header">
                                    <h5>Order Details</h5>
                                    <div className="order-info">
                                    <div className="info-item">
                                        <span className="info-label">Order Date:</span>
                                        <span className="info-value">09/22/2024</span>
                                    </div>
                                    <div className="info-item">
                                        <span className="info-label">Payment Method:</span>
                                        <span className="info-value">Credit Card (**** 7821)</span>
                                    </div>
                                    </div>
                                </div>
                                <div className="order-items-list">
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-8.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>In reprehenderit in voluptate</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-008</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$499.99</div>
                                    </div>
                                    <div className="order-item-detail">
                                    <div className="item-image">
                                        <img src="assets/img/product/product-9.webp" alt="Product" loading="lazy" />
                                    </div>
                                    <div className="item-info">
                                        <h6>Velit esse cillum dolore</h6>
                                        <div className="item-meta">
                                        <span className="item-sku">SKU: PRD-009</span>
                                        <span className="item-qty">Qty: 1</span>
                                        </div>
                                    </div>
                                    <div className="item-price">$399.99</div>
                                    </div>
                                </div>
                                <div className="order-summary">
                                    <div className="summary-row">
                                    <span>Subtotal:</span>
                                    <span>$899.98</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Shipping:</span>
                                    <span>$12.99</span>
                                    </div>
                                    <div className="summary-row">
                                    <span>Tax:</span>
                                    <span>$74.53</span>
                                    </div>
                                    <div className="summary-row total">
                                    <span>Total:</span>
                                    <span>$987.50</span>
                                    </div>
                                </div>
                                <div className="shipping-info">
                                    <div className="shipping-address">
                                    <h6>Shipping Address</h6>
                                    <p>123 Main Street<br/>Apt 4B<br/>New York, NY 10001<br/>United States</p>
                                    </div>
                                    <div className="shipping-method">
                                    <h6>Shipping Method</h6>
                                    <p>Standard Shipping (5-7 business days)</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                            {/* End Order Item */}

                        </div>

                        <div className="pagination-container">
                            <nav aria-label="Orders pagination">
                            <ul className="pagination">
                                <li className="page-item active"><a className="page-link" href="#">1</a></li>
                                <li className="page-item"><a className="page-link" href="#">2</a></li>
                                <li className="page-item"><a className="page-link" href="#">3</a></li>
                                <li className="page-item"><a className="page-link" href="#">4</a></li>
                            </ul>
                            </nav>
                        </div>
                        </div>
                    </div>

                    {/* Wishlist Tab */}
                    <div className={activeTab === "wishlist" ? "tab-pane fade show active" : "tab-pane fade"} id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                        <div className="tab-header">
                        <h2>Wishlist</h2>
                        </div>
                        <div className="wishlist-items">
                        <div className="row">
                            {/* Wishlist Item 1 */}
                            <div className="col-md-6 col-lg-4 mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                            <div className="wishlist-item">
                                <div className="wishlist-image">
                                <img src="assets/img/product/product-1.webp" alt="Product" loading="lazy" />
                                <button className="remove-wishlist" type="button">
                                    <i className="bi bi-x-lg"></i>
                                </button>
                                </div>
                                <div className="wishlist-content">
                                <h5>Lorem ipsum dolor sit amet</h5>
                                <div className="product-price">$129.99</div>
                                <button className="btn btn-add-cart">Add to cart</button>
                                </div>
                            </div>
                            </div>
                            {/* End Wishlist Item */}

                            {/* Wishlist Item 2 */}
                            <div className="col-md-6 col-lg-4 mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <div className="wishlist-item">
                                <div className="wishlist-image">
                                <img src="assets/img/product/product-2.webp" alt="Product" loading="lazy" />
                                <button className="remove-wishlist" type="button">
                                    <i className="bi bi-x-lg"></i>
                                </button>
                                </div>
                                <div className="wishlist-content">
                                <h5>Consectetur adipiscing elit</h5>
                                <div className="product-price">$89.50</div>
                                <button className="btn btn-add-cart">Add to cart</button>
                                </div>
                            </div>
                            </div>
                            {/* End Wishlist Item */}

                            {/* Wishlist Item 3 */}
                            <div className="col-md-6 col-lg-4 mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                            <div className="wishlist-item">
                                <div className="wishlist-image">
                                <img src="assets/img/product/product-3.webp" alt="Product" loading="lazy" />
                                <button className="remove-wishlist" type="button">
                                    <i className="bi bi-x-lg"></i>
                                </button>
                                </div>
                                <div className="wishlist-content">
                                <h5>Sed do eiusmod tempor</h5>
                                <div className="product-price">$199.99</div>
                                <button className="btn btn-add-cart">Add to cart</button>
                                </div>
                            </div>
                            </div>
                            {/* End Wishlist Item */}
                        </div>
                        </div>
                    </div>

                    {/* Payment Methods Tab */}
                    <div className={activeTab === "payment" ? "tab-pane fade show active" : "tab-pane fade"} id="payment" role="tabpanel" aria-labelledby="payment-tab">
                        <div className="tab-header">
                        <h2>Payment Methods</h2>
                        <button className="btn btn-add-payment" type="button">
                            <i className="bi bi-plus-lg"></i> Add payment method
                        </button>
                        </div>
                        <div className="payment-methods">
                        {/* Payment Method 1 */}
                        <div className="payment-method-item aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                            <div className="payment-card">
                            <div className="card-type">
                                <i className="bi bi-credit-card"></i>
                            </div>
                            <div className="card-info">
                                <div className="card-number">**** **** **** 4589</div>
                                <div className="card-expiry">Expires 09/2026</div>
                            </div>
                            <div className="card-actions">
                                <button className="btn-edit-card" type="button">
                                <i className="bi bi-pencil"></i>
                                </button>
                                <button className="btn-delete-card" type="button">
                                <i className="bi bi-trash"></i>
                                </button>
                            </div>
                            </div>
                            <div className="default-badge">Default</div>
                        </div>
                        {/* End Payment Method */}

                        {/* Payment Method 2 */}
                        <div className="payment-method-item aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <div className="payment-card">
                            <div className="card-type">
                                <i className="bi bi-credit-card"></i>
                            </div>
                            <div className="card-info">
                                <div className="card-number">**** **** **** 7821</div>
                                <div className="card-expiry">Expires 05/2025</div>
                            </div>
                            <div className="card-actions">
                                <button className="btn-edit-card" type="button">
                                <i className="bi bi-pencil"></i>
                                </button>
                                <button className="btn-delete-card" type="button">
                                <i className="bi bi-trash"></i>
                                </button>
                            </div>
                            </div>
                            <button className="btn btn-sm btn-make-default" type="button">Make default</button>
                        </div>
                        {/* End Payment Method */}
                        </div>
                    </div>

                    {/* Reviews Tab */}
                    <div className={activeTab === "reviews" ? "tab-pane fade show active" : "tab-pane fade"} id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div className="tab-header">
                        <h2>My Reviews</h2>
                        </div>
                        <div className="reviews-list">
                        {/* Review Item 1 */}
                        <div className="review-item aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                            <div className="review-header">
                            <div className="review-product">
                                <img src="assets/img/product/product-4.webp" alt="Product" className="product-image" loading="lazy" />
                                <div className="product-info">
                                <h5>Lorem ipsum dolor sit amet</h5>
                                <div className="review-date">Reviewed on 01/15/2025</div>
                                </div>
                            </div>
                            <div className="review-rating">
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star"></i>
                            </div>
                            </div>
                            <div className="review-content">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nisl nisl aliquam nisl, eget ultricies nisl nisl eget nisl.</p>
                            </div>
                            <div className="review-actions">
                            <button className="btn btn-sm btn-edit-review" type="button">Edit</button>
                            <button className="btn btn-sm btn-delete-review" type="button">Delete</button>
                            </div>
                        </div>
                        {/* End Review Item */}

                        {/* Review Item 2 */}
                        <div className="review-item aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <div className="review-header">
                            <div className="review-product">
                                <img src="assets/img/product/product-5.webp" alt="Product" className="product-image" loading="lazy" />
                                <div className="product-info">
                                <h5>Consectetur adipiscing elit</h5>
                                <div className="review-date">Reviewed on 12/03/2024</div>
                                </div>
                            </div>
                            <div className="review-rating">
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                                <i className="bi bi-star-fill"></i>
                            </div>
                            </div>
                            <div className="review-content">
                            <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                            <div className="review-actions">
                            <button className="btn btn-sm btn-edit-review" type="button">Edit</button>
                            <button className="btn btn-sm btn-delete-review" type="button">Delete</button>
                            </div>
                        </div>
                        {/* End Review Item */}
                        </div>
                    </div>

                    {/* Addresses Tab */}
                    <div className={activeTab === "addresses" ? "tab-pane fade show active" : "tab-pane fade"} id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                        <div className="tab-header">
                        <h2>My Addresses</h2>
                        <button className="btn btn-add-address" type="button">
                            <i className="bi bi-plus-lg"></i> Add new address
                        </button>
                        </div>
                        <div className="addresses-list">
                        <div className="row">
                            {/* Address Item 1 */}
                            <div className="col-lg-6 mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                            <div className="address-item">
                                <div className="address-header">
                                <h5>Home Address</h5>
                                <div className="address-actions">
                                    <button className="btn-edit-address" type="button">
                                    <i className="bi bi-pencil"></i>
                                    </button>
                                    <button className="btn-delete-address" type="button">
                                    <i className="bi bi-trash"></i>
                                    </button>
                                </div>
                                </div>
                                <div className="address-content">
                                <p>123 Main Street<br/>Apt 4B<br/>New York, NY 10001<br/>United States</p>
                                </div>
                                <div className="default-badge">Default</div>
                            </div>
                            </div>
                            {/* End Address Item */}

                            {/* Address Item 2 */}
                            <div className="col-lg-6 mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <div className="address-item">
                                <div className="address-header">
                                <h5>Work Address</h5>
                                <div className="address-actions">
                                    <button className="btn-edit-address" type="button">
                                    <i className="bi bi-pencil"></i>
                                    </button>
                                    <button className="btn-delete-address" type="button">
                                    <i className="bi bi-trash"></i>
                                    </button>
                                </div>
                                </div>
                                <div className="address-content">
                                <p>456 Business Ave<br/>Suite 200<br/>San Francisco, CA 94107<br/>United States</p>
                                </div>
                                <button className="btn btn-sm btn-make-default" type="button">Make default</button>
                            </div>
                            </div>
                            {/* End Address Item */}
                        </div>
                        </div>
                    </div>

                    {/* Notifications Tab */}
                    <div className={activeTab === "notifications" ? "tab-pane fade show active" : "tab-pane fade"} id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                        <div className="tab-header">
                        <h2>Notification Settings</h2>
                        </div>
                        <div className="notifications-settings aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <div className="notification-group">
                            <h5>Order Updates</h5>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">Order status changes</div>
                                <div className="notification-desc">Receive notifications when your order status changes</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="orderStatusNotif" checked={user?.notifications?.orderStatus} onChange={(e) => setUser({ ...user, notifications: { ...user.notifications, orderStatus: e.target.checked } })} />
                                <label className="form-check-label" htmlFor="orderStatusNotif"></label>
                            </div>
                            </div>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">Shipping updates</div>
                                <div className="notification-desc">Receive notifications about shipping and delivery</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="shippingNotif" checked={user?.notifications?.shipping} onChange={(e) => setUser({ ...user, notifications: { ...user.notifications, shipping: e.target.checked } })} />
                                <label className="form-check-label" htmlFor="shippingNotif"></label>
                            </div>
                            </div>
                        </div>

                        <div className="notification-group">
                            <h5>Account Activity</h5>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">Security alerts</div>
                                <div className="notification-desc">Receive notifications about security-related activity</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="securityNotif" checked={user?.notifications?.security} onChange={(e) => setUser({ ...user, notifications: { ...user.notifications, security: e.target.checked } })} />
                                <label className="form-check-label" htmlFor="securityNotif"></label>
                            </div>
                            </div>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">Password changes</div>
                                <div className="notification-desc">Receive notifications when your password is changed</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="passwordNotif" checked={user?.notifications?.password} onChange={(e) => setUser({ ...user, notifications: { ...user.notifications, password: e.target.checked } })} />
                                <label className="form-check-label" htmlFor="passwordNotif"></label>
                            </div>
                            </div>
                        </div>

                        <div className="notification-group">
                            <h5>Marketing</h5>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">Promotions and deals</div>
                                <div className="notification-desc">Receive notifications about special offers and discounts</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="promoNotif" />
                                <label className="form-check-label" htmlFor="promoNotif"></label>
                            </div>
                            </div>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">New product arrivals</div>
                                <div className="notification-desc">Receive notifications when new products are added</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="newProductNotif" />
                                <label className="form-check-label" htmlFor="newProductNotif"></label>
                            </div>
                            </div>
                            <div className="notification-item">
                            <div className="notification-info">
                                <div className="notification-title">Personalized recommendations</div>
                                <div className="notification-desc">Receive notifications with product recommendations based on your interests</div>
                            </div>
                            <div className="form-check form-switch">
                                <input className="form-check-input" type="checkbox" id="recommendNotif" checked={user?.notifications?.recommend} onChange={(e) => setUser({ ...user, notifications: { ...user.notifications, recommend: e.target.checked } })} />
                                <label className="form-check-label" htmlFor="recommendNotif"></label>
                            </div>
                            </div>
                        </div>

                        <div className="notification-actions">
                            <button type="button" className="btn btn-save">Save Preferences</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

            </div>

            </section>
    );
}

export default Profile;