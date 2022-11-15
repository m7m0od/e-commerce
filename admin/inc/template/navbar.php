
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN');?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-expanded="false"  >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <li><a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES');?></a></li>
                    <li><a class="nav-link " href="item.php"><?php echo lang('ITEMS');?></a></li>
                    <li><a class="nav-link"href="members.php"><?php echo lang('MEMBERS');?></a></li>
                    <li><a class="nav-link"href="comments.php"><?php echo lang('COMMENTS');?></a></li>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#"  role="button" data-bs-toggle="collapse" data-bs-target="#navbarDropdown" aria-haspopup="true" aria-expanded="false">mahmoud<span class="caret"></span> </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown" id="navbarDropdown">
                    <li><a class="dropdown-item" href="../index.php">Vist Shop</a></li>
                        <li><a class="dropdown-item" href="members.php?do=edit&UserID=<?php echo $_SESSION['UserID']?>"><?php echo lang('PROFILE');?></a></li>
                        <li><a class="dropdown-item" href="#"><?php echo lang('ADMIN_SETTINGS');?></a></li>
                        <li><a class="dropdown-item" href="logout.php?userOut"><?php echo lang('ADMIN_OUT');?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

