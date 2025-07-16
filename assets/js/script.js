document.addEventListener("DOMContentLoaded", function () {
  const sidebarToggleBtn = document.querySelector(".sidebar-toggle-btn");
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".sidebar-overlay");

  function toggleSidebar() {
    if (sidebar && overlay) {
      sidebar.classList.toggle("active");
      overlay.classList.toggle("active");
    }
  }

  if (sidebarToggleBtn) {
    sidebarToggleBtn.addEventListener("click", toggleSidebar);
  }

  if (overlay) {
    overlay.addEventListener("click", toggleSidebar);
  }
});
