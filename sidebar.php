<?php
$currentPage = basename($_SERVER['PHP_SELF']);

function sidebar_active($page, $currentPage)
{
    return $page === $currentPage ? ' active' : '';
}
?>
<aside class="sidebar">
    <div class="sidebar-brand">Client Panel</div>
    <a href="dashboard.php" class="sidebar-link<?php echo sidebar_active('dashboard.php', $currentPage); ?>">Dashboard</a>
    <a href="clients.php" class="sidebar-link<?php echo sidebar_active('clients.php', $currentPage); ?>">My Clients</a>
    <a href="client_form.php" class="sidebar-link<?php echo sidebar_active('client_form.php', $currentPage); ?>">Add Client</a>
    <a href="leads.php" class="sidebar-link<?php echo sidebar_active('leads.php', $currentPage); ?>">View Leads</a>
    <a href="add_lead.php" class="sidebar-link<?php echo sidebar_active('add_lead.php', $currentPage); ?>">Add Lead</a>
    <a href="monthly_sales.php" class="sidebar-link<?php echo sidebar_active('monthly_sales.php', $currentPage); ?>">Monthly Sales</a>
    <a href="monthly_sale_form.php" class="sidebar-link<?php echo sidebar_active('monthly_sale_form.php', $currentPage); ?>">Add Sale</a>
    <a href="details.php" class="sidebar-link<?php echo sidebar_active('details.php', $currentPage); ?>">My Account</a>
    <a href="index.php" class="sidebar-link<?php echo sidebar_active('index.php', $currentPage); ?>">Add User</a>
    <a href="logout.php" class="sidebar-link">Logout</a>
</aside>
