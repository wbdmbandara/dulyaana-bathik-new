<?php
  function setActiveMenuItem($active , $subMenu) {
    ?>
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'dashboard'){echo 'collapsed';} ?>" href="/dashboard">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'sarees'){echo 'collapsed';} ?>" data-bs-target="#sarees-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Sarees</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="sarees-nav" class="nav-content <?php if($active != 'sarees'){echo 'collapse';} ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/sarees" class="<?php if($subMenu == 'sarees'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Sarees</span>
            </a>
          </li>
          <li>
            <a href="/new-saree" class="<?php if($subMenu == 'new-saree'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>New Saree</span>
            </a>
          </li>
          <li>
            <a href="/categories" class="<?php if($subMenu == 'categories'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Categories</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'users'){echo 'collapsed';} ?>" href="/users">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'customers'){echo 'collapsed';} ?>" href="/customers">
          <i class="bi bi-people"></i>
          <span>Customers</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'payments'){echo 'collapsed';} ?>" data-bs-target="#payments-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-credit-card"></i><span>Payments</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="payments-nav" class="nav-content <?php if($active != 'payments'){echo 'collapse';} ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/bank-details" class="<?php if($subMenu == 'bank-details'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Bank Details</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'home-page'){echo 'collapsed';} ?>" data-bs-target="#home-page-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Home Page</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="home-page-nav" class="nav-content <?php if($active != 'home-page'){echo 'collapse';} ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/home-slider" class="<?php if($subMenu == 'home-slider'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Home Slider</span>
            </a>
          </li>
          <li>
            <a href="/promo-cards" class="<?php if($subMenu == 'promo-cards'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Promo Cards</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($active != 'appearance'){echo 'collapsed';} ?>" data-bs-target="#appearance-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Appearance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="appearance-nav" class="nav-content <?php if($active != 'appearance'){echo 'collapse';} ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/theme-settings" class="<?php if($subMenu == 'theme-settings'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Theme Settings</span>
            </a>
          </li>
          <li>
            <a href="/footer" class="<?php if($subMenu == 'footer'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Footer</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item d-none">
        <a class="nav-link <?php if($active != 'settings'){echo 'collapsed';} ?>" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="settings-nav" class="nav-content <?php if($active != 'settings'){echo 'collapse';} ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/email-settings" class="<?php if($subMenu == 'email-settings'){echo 'active';} ?>">
              <i class="bi bi-circle"></i><span>Email Settings</span>
            </a>
          </li>
        </ul>
      </li>


      <!-- <li class="nav-heading">Pages</li> -->

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-file-earmark"></i>
          <span>Blank</span>
        </a>
      </li> -->

    </ul>

  </aside>

  <?php
  }
?>