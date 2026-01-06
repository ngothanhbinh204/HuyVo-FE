// ================================================
// HEADER MODULE
// ================================================

export const header = {
  init: () => {
    const headerEl = document.querySelector(".header");
    if (!headerEl) return;

    let lastScrollY = window.scrollY;

    // ----------------------------------------
    // Sticky Header on Scroll
    // ----------------------------------------
    const handleScroll = () => {
      const currentScrollY = window.scrollY;

      // Add background/style when scrolled a bit
      if (currentScrollY > 50) {
        headerEl.classList.add("scrolled");
      } else {
        headerEl.classList.remove("scrolled");
      }

      // Hide/Show logic based on scroll direction
      if (currentScrollY > lastScrollY && currentScrollY > 100) {
        // Scrolling DOWN
        headerEl.classList.add("header-hidden");
        
        // Optional: Close any open mega menus for cleaner UX
        headerEl.classList.remove("menu-open");
        document.querySelectorAll(".header .menu-item.has-dropdown.active").forEach(item => item.classList.remove("active"));
      } else {
        // Scrolling UP
        headerEl.classList.remove("header-hidden");
      }

      lastScrollY = currentScrollY;
    };

    window.addEventListener("scroll", handleScroll);
    handleScroll();

    // ----------------------------------------
    // Desktop Mega Menu - Click Toggle
    // ----------------------------------------
    const menuItems = document.querySelectorAll(
      ".header .menu-item.has-dropdown"
    );

    menuItems.forEach((item) => {
      const link = item.querySelector(":scope > .header-link");

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
            headerEl.classList.add("menu-open");
          } else {
            headerEl.classList.remove("menu-open");
          }
        });
      }
    });

    // Close mega menu when clicking outside
    document.addEventListener("click", (e) => {
      if (!e.target.closest(".header .menu-item.has-dropdown")) {
        menuItems.forEach((item) => item.classList.remove("active"));
        headerEl.classList.remove("menu-open");
      }
    });

    // ----------------------------------------
    // Mega Menu - Submenu Level 2 Toggle
    // ----------------------------------------
    const submenuToggles = document.querySelectorAll(
      ".mega-menu .menu-list .has-submenu > a"
    );

    submenuToggles.forEach((toggle) => {
      toggle.addEventListener("click", (e) => {
        e.preventDefault();
        const parent = toggle.closest(".has-submenu");

        // Toggle active state
        parent.classList.toggle("active");
      });
    });

    // ----------------------------------------
    // Mobile Hamburger Toggle
    // ----------------------------------------
    const hamburger = document.querySelector(".header-hamburger");
    const mobileMenu = document.querySelector(".mobile-menu");
    const mobileMenuClose = document.querySelector(".mobile-menu-close");
    const mobileMenuOverlay = document.querySelector(".mobile-menu-overlay");

    const openMobileMenu = () => {
      hamburger?.classList.add("active");
      mobileMenu?.classList.add("active");
      document.body.classList.add("overflow-hidden");

      // Requirement: Open ALL submenus by default
      const allSubmenus = mobileMenu?.querySelectorAll(".menu-item-has-children");
      allSubmenus?.forEach((item) => {
        item.classList.add("active");
      });
    };

    const closeMobileMenu = () => {
      hamburger?.classList.remove("active");
      mobileMenu?.classList.remove("active");
      document.body.classList.remove("overflow-hidden");
    };

    hamburger?.addEventListener("click", () => {
      if (mobileMenu?.classList.contains("active")) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    mobileMenuClose?.addEventListener("click", closeMobileMenu);
    mobileMenuOverlay?.addEventListener("click", closeMobileMenu);

    // ----------------------------------------
    // Mobile Menu - Accordion Logic
    // ----------------------------------------
    const handleAccordionToggle = (e) => {
      e.preventDefault();
      const toggle = e.currentTarget;
      // Determine the main menu item container
      const menuItem = toggle.closest(".menu-item");
      if (!menuItem) return;

      const parentList = menuItem.closest("ul");
      if (!parentList) return;

      // Identify siblings that are also menu items (and likely have children/submenus)
      const siblings = Array.from(parentList.children).filter(
        (child) =>
          child !== menuItem && child.classList.contains("menu-item")
      );

      // Check state
      const isCurrentActive = menuItem.classList.contains("active");
      // Are any other siblings currently open?
      const hasOtherActive = siblings.some((sib) => sib.classList.contains("active"));

      // Logic:
      // 1. If currently transitioning from "All/Many Open" to "Focus One":
      //    (Implied by having other siblings active, OR simply enforcing "Close Others" rule)
      //    We close all siblings and Ensure current is Active.
      // 2. If standard single-accordion state (others closed):
      //    We just toggle current.
      
      if (hasOtherActive) {
        // Close all siblings
        siblings.forEach((sib) => sib.classList.remove("active"));
        // Force open current (Focus intent)
        menuItem.classList.add("active");
      } else {
        // Standard toggle (Open <-> Close)
        menuItem.classList.toggle("active");
      }
    };

    // Apply to Category Toggles (Buttons)
    const mobileSubmenuToggles = document.querySelectorAll(
      ".mobile-menu .submenu-toggle"
    );
    mobileSubmenuToggles.forEach((toggle) => {
      toggle.addEventListener("click", handleAccordionToggle);
    });

    // Apply to Category Titles (Links)
    const categoryTitles = document.querySelectorAll(
      ".mobile-menu .category-title"
    );
    categoryTitles.forEach((title) => {
      title.addEventListener("click", handleAccordionToggle);
    });

    // Apply to Nested Submenus (Level 2 Links)
    // Select links inside .has-submenu that are direct children (or intended toggles)
    const mobileNestedToggles = document.querySelectorAll(
      ".mobile-menu .sub-menu .has-submenu > a"
    );
    mobileNestedToggles.forEach((toggle) => {
      toggle.addEventListener("click", handleAccordionToggle);
    });

    // ----------------------------------------
    // Search Overlay Toggle
    // ----------------------------------------
    const searchBtn = document.querySelector(".header .search-btn");
    const searchOverlay = document.querySelector(".header-search-overlay");
    const searchClose = document.querySelector(
      ".header-search-overlay .search-close"
    );
    const searchBackdrop = document.querySelector(".search-overlay-backdrop");
    const searchInput = document.querySelector(
      ".header-search-overlay .search-input"
    );

    const openSearch = () => {
      searchOverlay?.classList.add("active");
      document.body.classList.add("overflow-hidden");

      // Focus input after animation
      setTimeout(() => {
        searchInput?.focus();
      }, 300);
    };

    const closeSearch = () => {
      searchOverlay?.classList.remove("active");
      document.body.classList.remove("overflow-hidden");

      if (searchInput) {
        searchInput.value = "";
      }
    };

    searchBtn?.addEventListener("click", openSearch);
    searchClose?.addEventListener("click", closeSearch);
    searchBackdrop?.addEventListener("click", closeSearch);

    // Close search/menu with ESC key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        if (searchOverlay?.classList.contains("active")) {
          closeSearch();
        }
        if (mobileMenu?.classList.contains("active")) {
          closeMobileMenu();
        }
      }
    });

    // ----------------------------------------
    // Prevent search form submission on empty input
    // ----------------------------------------
    const searchForm = document.querySelector(
      ".header-search-overlay .search-form"
    );

    searchForm?.addEventListener("submit", (e) => {
      if (!searchInput?.value.trim()) {
        e.preventDefault();
        searchInput?.focus();
      }
    });
  },
};
