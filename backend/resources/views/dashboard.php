<?php
  include_once 'common/header.php';
  setTitle('Dashboard');
  include_once 'common/topbar.php';
  include_once 'common/sidebar.php';
  setActiveMenuItem('dashboard', '');
  $selectedFilterText = ucwords(str_replace('-', ' ', $selectedFilter));
?>

  <main id="main" class="main">

    <div class="pagetitle">
      <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard</h1>
        <form action="" method="get">
          <div class="filter d-flex align-items-center me-4">
            <label for="filter-select" class="form-label me-2">Filter:</label>
            <select id="filter-select" class="form-select" name="filter" style="width: auto;" onchange="this.form.submit()">
              <option <?php if ($selectedFilter === 'today') echo 'selected'; ?> value="today">Today</option>
              <option <?php if ($selectedFilter === 'this-week') echo 'selected'; ?> value="this-week">This Week</option>
              <option <?php if ($selectedFilter === 'this-month') echo 'selected'; ?> value="this-month">This Month</option>
              <option <?php if ($selectedFilter === 'this-year') echo 'selected'; ?> value="this-year">This Year</option>
            </select>
          </div>
        </form>
      </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= url('/'); ?>">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="row">

          
          <!-- Revenue Card -->
          <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">

                <div class="card-body">
                <h5 class="card-title">Revenue <span>| <?php echo $selectedFilterText; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                  <h6>Rs.<?php echo number_format($todayRevenue); ?></h6>
                  <?php echo $revenueChangeDisplay; ?>

                  </div>
                </div>
                </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Sales Card -->
          <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Orders <span>| <?php echo $selectedFilterText; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cart"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $totalOrdersDisplay; ?></h6>
                    <?php echo $ordersChangeDisplay; ?>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->

          <!-- Pending Orders Card -->
          <div class="col-xxl-3 col-md-6">

            <div class="card info-card pending-orders-card">

              <div class="card-body">
              <h5 class="card-title">Pending Orders</h5>

              <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-cart"></i>
              </div>
              <div class="ps-3">
              <h6>Rs.<?php echo number_format($pendingOrdersValue); ?></h6>
              <span class="text-muted small pt-2 ps-1"><?php echo $pendingOrders; ?> Orders</span>

              </div>
              </div>

              </div>
            </div>

          </div><!-- End Pending Orders Card -->

          <!-- Pending Bank Transfer Card -->
          <div class="col-xxl-3 col-md-6">

            <div class="card info-card pending-transfer-card">

              <div class="card-body">
              <h5 class="card-title">Pending Bank Transfer</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-cash"></i>
                </div>
                <div class="ps-3">
                <h6>Rs.<?php echo number_format($pendingBankTransfersValue); ?></h6>
                <span class="text-muted small pt-2 ps-1"><?php echo $pendingBankTransfers; ?> Orders</span>

                </div>
              </div>

              </div>
            </div>

          </div><!-- End Pending Bank Transfer Card -->

          <!-- Revenue Trends by Payment Method -->
          <div class="col-12">
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Revenue Trends by Payment Method <span>| <?php echo $selectedFilterText; ?></span></h5>

                <!-- Line Chart -->
                <div id="reportsChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#reportsChart"), {
                    series: [{
                      name: 'Revenue',
                      data: [<?php echo implode(',', array_column($salesChart, 'revenue')); ?>]
                      }, {
                      name: 'Bank Transfers',
                      data: [<?php echo implode(',', array_column($salesChart, 'bankTransfers')); ?>]
                      }, {
                      name: 'Cash on Delivery',
                      data: [<?php echo implode(',', array_column($salesChart, 'cod')); ?>]
                    }],
                    chart: {
                      height: 350,
                      type: 'area',
                      toolbar: {
                        show: false
                      },
                    },
                    markers: {
                    size: 4
                    },
                    colors: ['#4154f1', '#2eca6a', '#ff771d', '#e74c3c'],
                    fill: {
                      type: "gradient",
                      gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.4,
                        stops: [0, 90, 100]
                      }
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      curve: 'smooth',
                      width: 2
                    },
                    xaxis: {
                      type: 'datetime',
                      categories: [<?php $dates = array_map(function($d) { return '"' . $d['date'] . '"'; }, $salesChart); echo implode(',', $dates); ?>]
                    },
                    yaxis: {
                      title: {
                        text: '(Rs)'
                      }
                    },
                    tooltip: {
                      x: {
                          format: 'dd/MM/yy'
                        },
                      }
                    }).render();
                  });
                </script>
                <!-- End Line Chart -->

              </div>

            </div>
          </div><!-- End Revenue Trends by Payment Method -->

          <!-- Recent Sales -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="card-body">
                <h5 class="card-title">Recent Sales <span>| <?php echo $selectedFilterText; ?></span></h5>

                <table class="table table-bordered">
                  <thead>
                    <tr class="text-center">
                      <th scope="col">Order ID</th>
                      <th scope="col">Customer</th>
                      <th scope="col">Product</th>
                      <th scope="col">QTY</th>
                      <th scope="col">Price</th>
                      <th scope="col">Value</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($recentSales as $sale): ?>
                      <?php
                        $orderStatus = htmlspecialchars($sale['status']);
                        $orderStatusBg = '';
                        $orderStatusTxtColor = '';
                        switch (strtolower($orderStatus)) {
                          case 'pending':
                            $orderStatusBg = '#fff3cd';
                            // $orderStatusTxtColor = '#856404';
                            break;
                          case 'completed':
                            $orderStatusBg = '#d1e7dd';
                            $orderStatusTxtColor = '#0f5132';
                            break;
                          case 'cancelled':
                            $orderStatusBg = '#f8d7da';
                            $orderStatusTxtColor = '#721c24';
                            break;
                          case 'processing':
                            $orderStatusBg = '#cce5ff';
                            $orderStatusTxtColor = '#000000';
                            break;
                          case 'shipped':
                            $orderStatusBg = '#0d6efd';
                            $orderStatusTxtColor = '#ffffff';
                            break;
                          default:
                            $orderStatusBg = '#6c757d';
                            $orderStatusTxtColor = '#000000';
                        }  
                      ?>
                      <tr>
                        <th class="text-center" scope="row"><?php echo htmlspecialchars($sale['id']); ?></th>
                        <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($sale['item_name']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($sale['quantity']); ?></td>
                        <td class="text-end">Rs. <?php echo number_format($sale['price'], 2); ?></td>
                        <td class="text-end">Rs. <?php echo number_format($sale['quantity'] * $sale['price'], 2); ?></td>
                        <td class="text-center text-capitalize" style="background-color: <?php echo $orderStatusBg; ?>; color: <?php echo $orderStatusTxtColor; ?>;"><?php echo htmlspecialchars($sale['status']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

              </div>

            </div>
          </div><!-- End Recent Sales -->

          <!-- Top Selling -->
          <div class="col-12">
            <div class="card top-selling overflow-auto">

              <div class="card-body pb-0">
                <h5 class="card-title">Top Selling <span>| <?php echo $selectedFilterText; ?></span></h5>

                <table class="table table-bordered">
                  <thead>
                  <tr class="text-center">
                    <th scope="col">Image</th>
                    <th scope="col">Category</th>
                    <th scope="col">Product</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Price</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Revenue</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($topSelling as $item): ?>
                    <tr>
                    <th scope="row" class="text-center"><img src="<?php echo htmlspecialchars($item['main_image']); ?>" alt=""></th>
                    <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($item['stock_quantity']); ?></td>
                    <td class="text-end">Rs. <?php echo number_format($item['total_revenue'] / $item['total_quantity'], 2); ?></td>
                    <td class="fw-bold text-center"><?php echo htmlspecialchars($item['total_quantity']); ?></td>
                    <td class="fw-bold text-end">Rs. <?php echo number_format($item['total_revenue'], 2); ?></td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>

              </div>

            </div>
          </div><!-- End Top Selling -->

        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4 d-none">

          <!-- Recent Activity -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">

                <div class="activity-item d-flex">
                  <div class="activite-label">32 min</div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Voluptatem blanditiis blanditiis eveniet
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    Voluptates corrupti molestias voluptatem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">1 day</div>
                  <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                  <div class="activity-content">
                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 days</div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    Est sit eum reiciendis exercitationem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">4 weeks</div>
                  <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                  <div class="activity-content">
                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                  </div>
                </div><!-- End activity item-->

              </div>

            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Budget Report <span>| This Month</span></h5>

              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                    legend: {
                      data: ['Allocated Budget', 'Actual Spending']
                    },
                    radar: {
                      // shape: 'circle',
                      indicator: [{
                          name: 'Sales',
                          max: 6500
                        },
                        {
                          name: 'Administration',
                          max: 16000
                        },
                        {
                          name: 'Information Technology',
                          max: 30000
                        },
                        {
                          name: 'Customer Support',
                          max: 38000
                        },
                        {
                          name: 'Development',
                          max: 52000
                        },
                        {
                          name: 'Marketing',
                          max: 25000
                        }
                      ]
                    },
                    series: [{
                      name: 'Budget vs spending',
                      type: 'radar',
                      data: [{
                          value: [4200, 3000, 20000, 35000, 50000, 18000],
                          name: 'Allocated Budget'
                        },
                        {
                          value: [5000, 14000, 28000, 26000, 42000, 21000],
                          name: 'Actual Spending'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Website Traffic <span>| Today</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: 1048,
                          name: 'Search Engine'
                        },
                        {
                          value: 735,
                          name: 'Direct'
                        },
                        {
                          value: 580,
                          name: 'Email'
                        },
                        {
                          value: 484,
                          name: 'Union Ads'
                        },
                        {
                          value: 300,
                          name: 'Video Ads'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Website Traffic -->

          <!-- News & Updates Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

              <div class="news">
                <div class="post-item clearfix">
                  <img src="assets/img/news-1.jpg" alt="">
                  <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                  <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-2.jpg" alt="">
                  <h4><a href="#">Quidem autem et impedit</a></h4>
                  <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-3.jpg" alt="">
                  <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-4.jpg" alt="">
                  <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                  <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-5.jpg" alt="">
                  <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                  <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
                </div>

              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->

        </div><!-- End Right side columns -->

      </div>

      <!-- under construction -->
      <div class="row d-none">
        <div class="col-12">
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
            <div>
              <strong>Under Construction!</strong> This section is currently under development. Please check back later for updates.
            </div>
          </div>
        </div>
    </section>

  </main><!-- End #main -->

<?php
    include_once 'common/footer.php';
?>