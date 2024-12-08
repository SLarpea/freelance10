<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, onBeforeMount, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3'

defineProps({
  module: String,
});

const page = usePage();

const userPermissions = computed(() => page.props.auth.user.permissions);
const userRole = computed(() => page.props.auth.user.roles[0].name);

const initTreeview = () => {
            // Initialize AdminLTE's treeview for the sidebar
    $('[data-widget="treeview"]').Treeview('init');
};

const handleMobileSidebarCollapse = () => {
    $(document).on('click', '.nav-link', () => {
        if (window.innerWidth < 768) {
            $('body').removeClass('sidebar-open').addClass('sidebar-closed sidebar-collapse');
        }
    });
};

onMounted(() => {
    initTreeview();
    handleMobileSidebarCollapse();
});

onBeforeMount(() => {
    // Clean up the event listener
    $(document).off('click', '.nav-link');
});

</script>

<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4">
    <div class="brand-logo">
      <i class="bi bi-person-workspace"></i>
      <span>FreeTaste10</span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
          <li class="nav-item" v-if="userRole == 'Superadmin' || userPermissions.includes('view dashboard')" :class="{ 'active': module === 'Dashboard' }">
            <Link data-toggle="collapse" data-target=".navbar-collapse" href="/dashboard" class="nav-link text-sm">
              <i class="nav-icon fas fa-th"></i>
              <span>Dashboard</span>
            </Link>
          </li>
          <li class="nav-header">MODULES</li>
          <li class="nav-item" v-if="userRole == 'Superadmin' || userPermissions.includes('view shops')" :class="{ 'active': module === 'Shops' }">
            <Link data-toggle="collapse" data-target=".navbar-collapse" href="/shops" class="nav-link text-sm">
              <i class="nav-icon bi bi-shop-window"></i>
              <span>Shops</span>
            </Link>
          </li>
          <li class="nav-header">SETTINGS</li>
          <li class="nav-item" v-if="userRole == 'Superadmin' || userPermissions.includes('view roles')" :class="{ 'active': module === 'Roles' }">
            <Link href="/roles" class="nav-link text-sm">
              <i class="nav-icon bi bi-person-circle"></i>
              <span>Roles</span>
            </Link>
          </li>
          <li class="nav-item" v-if="userRole == 'Superadmin' || userPermissions.includes('view user roles')" :class="{ 'active': module === 'User Roles' }">
            <Link href="/user-roles" class="nav-link text-sm">
              <i class="nav-icon bi bi-person-bounding-box"></i>
              <span>User Roles</span>
            </Link>
          </li>
          <li class="nav-item">
            <a href="pages/calendar.html" class="nav-link text-sm">
              <i class="nav-icon far fa-calendar-alt"></i>
              <span>Calendar</span>
              <span class="badge badge-info right">2</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-sm">
              <i class="nav-icon fas fa-search"></i>
              <span>Search</span>
              <i class="fas fa-angle-left right"></i>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item active">
                <a href="pages/search/simple.html" class="nav-link text-sm">
                  <i class="far fa-circle nav-icon"></i>
                  <span>Simple Search</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link text-sm">
                  <i class="far fa-circle nav-icon"></i>
                  <span>Enhanced</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">API DOCUMENTATION</li>
          <li class="nav-item" :class="{ 'active': module === 'Swagger' }">
            <Link href="/swagger/docu" class="nav-link text-sm">
              <i class="nav-icon bi bi-braces"></i>
              <span>Swagger</span>
            </Link>
          </li>
          <li class="nav-header">WEB APP TRACKER</li>
          <li class="nav-item" :class="{ 'active': module === 'Pulse' }">
            <Link href="/pulse/track" class="nav-link text-sm">
              <i class="nav-icon bi bi-clipboard2-pulse"></i>
              <span>Pulse</span>
            </Link>
          </li>
          <li class="nav-item" :class="{ 'active': module === 'Activity Logs' }">
            <Link href="/logs" class="nav-link text-sm">
              <i class="nav-icon bi bi-list-columns-reverse"></i>
              <span>Activity Logs</span>
            </Link>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>