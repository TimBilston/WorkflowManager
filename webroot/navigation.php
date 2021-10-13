<?php
$this->loadHelper('Authentication.Identity');

if ($this->Identity->isLoggedIn()) {
$currentUserName = $this->Identity->get('name');
$currentUserId = $this->Identity->get('id');
}?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<nav id="navbar" class="rvnm-navbar-box dark-ruby">

<a href="<?php echo $this->Url->build(['controller' => 'pages', 'action' => 'display'])?>">
    <?php echo $this->Html->image('logo.png', ['fullBase' => true, 'class' => 'logo']) ?>
</a>

    <!-- base64 for logo.png -->
    <li>
    <i class="fa fa-tasks"></i>
        <?= $this->Html->link(__('New Task'), ['controller' => 'tasks', 'action' => 'add'], ['class' => 'text']) ?>
    </li>
    <li>
    <i class="fa fa-user-circle"></i>
        <?= $this->Html->link(__('View Employees'), ['controller' => 'Users', 'action' => 'index'], ['class' => 'text']) ?>
    </li>
    <li>
            <i class="fa fa-bar-chart"></i>
            <?= $this->Html->link(__('KPI'), ['controller' => 'kpi'], ['class' => 'text']) ?>
        </li>
    <li>
    <i class="fa fa-users"></i>
        <?= $this->Html->link(__('View Clients'), ['controller' => 'Clients', 'action' => 'index'], ['class' => 'text']) ?>
    </li>
    <li>
        <i class="fa fa-user-circle"></i>
        <?= $this->Html->link(__('My Account'), ['controller' => 'Users',  'action' => 'view',  $currentUserId], ['class' => 'text']) ?>
    </li>
    <li>
    <i class="fa fa-sign-out"></i>
        <?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout'], ['class' => 'text']) ?>
    </li>

</nav>

<style>

.rvnm-navbar-box {
    position:fixed;
    left: 0px;
    top: 0;
    width: 220px;
    background: #4387f5;
    min-height: 50vh;
    overflow-y: hidden;
    z-index: 99;
    height: 100%;
}
.rvnm-navbar-box.dark-ruby {
    background:#FFFFFF;
}

.rvnm-navbar-box.dark-ruby li{
    border-bottom: 1px solid rgb(184 12 60 / 16%);
    text-align: left;
    list-style: none;
    height: 70px;
    line-height:80px;
    background: #FFFFFF;
    margin-bottom: 0rem;
    padding-left:20px;
}

.rvnm-navbar-box.dark-ruby li a{
    text-decoration: none;
    height: 100%;
    color: #353C48;
    font-size:16px;
    font-family: "Lato", sans-serif;
    letter-spacing:0px;
    padding-left:8px;
    font-weight:bold;

}
.rvnm-navbar-box.dark-ruby li:hover a {
    background:#b80c3c;
    color:#fff;
}
.rvnm-navbar-box.dark-ruby li:hover{
    background:#b80c3c;
}
.logo{
    width:100%;
    height:80px!important;
    padding:10px;
    box-sizing: border-box;
    margin-bottom: 30px;

}
.logo img{
    width:100%;
    height:180px!important;
    display:block;
    cursor: pointer;
}


/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    top: 2px;

    margin-left: auto;
    margin-right: 10px;
    width: 40px;
    height: 24px;
}

/* Hide default HTML checkbox */
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
    -webkit-transform: translateX(16px);
    -ms-transform: translateX(16px);
    transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 22px;
}

.slider.round:before {
    border-radius: 50%;
}


</style>


