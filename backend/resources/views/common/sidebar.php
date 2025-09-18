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