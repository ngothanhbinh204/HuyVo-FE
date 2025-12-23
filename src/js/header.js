export const header = {
  init: () => {
    const headerEl = document.querySelector(".header");
    if (!headerEl) return;

    let lastScrollY = window.scrollY;

    // Sticky Header on Scroll
    const handleScroll = () => {
      const currentScrollY = window.scrollY;

      if (currentScrollY > 50) {
        headerEl.classList.add("active");
      } else {
        headerEl.classList.remove("active");
      }

      lastScrollY = currentScrollY;
    };

    window.addEventListener("scroll", handleScroll);
    handleScroll();

    // Mobile Hamburger Toggle
    const hamburger = document.querySelector(".header-hamburger");
    const mobileMenu = document.querySelector(".mobile-menu");

    if (hamburger && mobileMenu) {
      hamburger.addEventListener("click", () => {
        hamburger.classList.toggle("active");
        mobileMenu.classList.toggle("active");
        document.body.classList.toggle("overflow-hidden");
      });
    }

    // Desktop Mega Menu - Click Toggle
    const menuItems = document.querySelectorAll(
      ".header .menu-item.has-dropdown"
    );

    menuItems.forEach((item) => {
      const link = item.querySelector(".header-link");

      if (link) {
        link.addEventListener("click", (e) => {
          e.preventDefault();

          // Check if this item is already active
          const isActive = item.classList.contains("active");

          // Close all other menus
          menuItems.forEach((otherItem) => {
            otherItem.classList.remove("active");
          });

          // Toggle current menu
          if (!isActive) {
            item.classList.add("active");
          }
        });
      }
    });

    // Close mega menu when clicking outside
    document.addEventListener("click", (e) => {
      if (!e.target.closest(".header .menu-item")) {
        menuItems.forEach((item) => item.classList.remove("active"));
      }
    });

    // Submenu Level 2 Toggle (for mobile and desktop)
    const submenuToggles = document.querySelectorAll(
      ".menu-list .has-submenu > a"
    );

    submenuToggles.forEach((toggle) => {
      toggle.addEventListener("click", (e) => {
        if (window.innerWidth >= 1024) {
          // Desktop: prevent default and toggle
          e.preventDefault();
          const parent = toggle.closest(".has-submenu");
          parent.classList.toggle("active");
        }
      });
    });

    // Search Overlay Toggle
    const searchBtn = document.querySelector(".header .search-btn");
    const searchOverlay = document.querySelector(".header-search-overlay");
    const searchClose = document.querySelector(".search-close");
    const searchInput = document.querySelector(".search-input");

    if (searchBtn && searchOverlay) {
      // Open search
      searchBtn.addEventListener("click", () => {
        searchOverlay.classList.add("active");
        document.body.classList.add("overflow-hidden");
        // Focus input after animation
        setTimeout(() => {
          if (searchInput) searchInput.focus();
        }, 300);
      });

      // Close search - close button
      if (searchClose) {
        searchClose.addEventListener("click", () => {
          closeSearchOverlay();
        });
      }

      // Close search - click outside
      searchOverlay.addEventListener("click", (e) => {
        if (e.target === searchOverlay) {
          closeSearchOverlay();
        }
      });

      // Close search - ESC key
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && searchOverlay.classList.contains("active")) {
          closeSearchOverlay();
        }
      });

      function closeSearchOverlay() {
        searchOverlay.classList.remove("active");
        document.body.classList.remove("overflow-hidden");
        if (searchInput) searchInput.value = "";
      }
    }
  },
};
