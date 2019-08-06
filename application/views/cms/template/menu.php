<li class="menu active" id="dashboard">
    <a href="<?php echo site_url('view/home');?>">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span> </i>
    </a>
</li>

<li class="treeview menu" id="group-master-date">
    <a href="#">
        <i class="fa fa-cubes"></i>
        <span>Master Data</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li><a href="#" onclick="loadContent(base_url + 'view/_m_rawmaterial'); return false;"><i class="fa fa-cube"></i> Raw Material</a></li>
    </ul>
</li>

<li class="treeview menu" id="group-quotation">
    <a href="#">
        <i class="fa fa-handshake-o"></i>
        <span>Quotation</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li><a href="#" onclick="loadContent(base_url + 'view/_quotation'); return false;"><i class="fa fa-circle-o"></i> Quotation</a></li>
    </ul>
</li>
<li class="menu" id="dashboard">
    <a href="#" onclick="goToSomewhere('http://server/project_inquiry/table_inquiry1.php')">
        <i class="fa fa-file-text-o"></i> <span>List Quotation</span> </i>
    </a>
</li>


<!-- <li class="treeview menu" id="group-system">
    <a href="#">
        <i class="fa fa-cogs"></i>
        <span>System</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li><a href="#" onclick="loadContent(base_url + 'view/_users'); return false;"><i class="fa fa-circle-o"></i> Users</a></li>
        <li><a href="#" onclick="loadContent(base_url + 'view/_user_groups'); return false;"><i class="fa fa-circle-o"></i> User Groups</a></li>
        <li><a href="#" onclick="loadContent(base_url + 'view/_group_access'); return false;"><i class="fa fa-circle-o"></i> Group Access</a></li>
    </ul>
</li> -->

