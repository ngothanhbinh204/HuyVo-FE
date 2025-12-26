import Swiper from "swiper";
import {
  Autoplay,
  EffectFade,
  Grid,
  Mousewheel,
  Navigation,
  Pagination,
  Thumbs,
  Controller,
} from "swiper/modules";

/**
 * About Page Scripts
 * Handles Timeline Swiper and Interactions
 */

export const initAboutCmp = () => {
  const timelineSection = document.getElementById("timeline-section");
  if (!timelineSection) return;

  // Initialize Swiper
  const swiperContainer = timelineSection.querySelector(".timeline-slider");
  if (swiperContainer) {
    const timelineSwiper = new Swiper(swiperContainer, {
      modules: [Navigation, EffectFade],
      speed: 800,
      effect: "fade",
      fadeEffect: {
        crossFade: true,
      },
      navigation: {
        prevEl: ".slider-controls .btn-nav.prev",
        nextEl: ".slider-controls .btn-nav.next",
      },
      on: {
        slideChange: function () {
          updateActiveDot(this.activeIndex);
        },
      },
    });

    const dots = timelineSection.querySelectorAll(".dots-list .dot-item");
    const progressBar = timelineSection.querySelector(".timeline-progress");

    // Click Event
    dots.forEach((dot) => {
      dot.addEventListener("click", function () {
        const index = parseInt(this.getAttribute("data-index"));
        if (!isNaN(index)) {
          timelineSwiper.slideTo(index);
        }
      });
    });

    // Handle Window Resize
    window.addEventListener("resize", () => {
      updateActiveDot(timelineSwiper.activeIndex);
    });

    // Main Function to Update State
    function updateActiveDot(index) {
      // 1. Determine breakpoint and limits
      const width = window.innerWidth;
      let maxVisible = dots.length; // Default desktop (>= 1280)

      if (width < 768) {
        maxVisible = 3;
      } else if (width < 1280) {
        maxVisible = 5;
      }

      // 2. Calculate Visible Range (Center active index)
      let start = 0;
      let end = dots.length - 1;

      if (maxVisible < dots.length) {
        const half = Math.floor(maxVisible / 2);
        start = index - half;
        end = index + half;

        // Adjust boundaries
        if (start < 0) {
          start = 0;
          end = maxVisible - 1;
        } else if (end >= dots.length) {
          end = dots.length - 1;
          start = dots.length - maxVisible;
        }
      }

      // 3. Update Visibility and Active Classes
      let visibleCount = 0;
      let relativeActiveIndex = 0;

      dots.forEach((d, i) => {
        // Reset classes
        d.classList.remove("current", "active", "is-hidden");
        
        // Visibility Check
        if (i >= start && i <= end) {
          d.style.display = ""; // Show
          visibleCount++;
          if (i === index) relativeActiveIndex = i - start;
        } else {
          d.classList.add("is-hidden"); // Hide class for Sass
          d.style.display = "none";     // Inline fallback
        }

        // Active State (All previous items active)
        if (i <= index) {
          d.classList.add("active");
        }
        // Current State
        if (i === index) {
          d.classList.add("current");
        }
      });

      // 4. Update Progress Bar
      if (progressBar && visibleCount > 1) {
        // Calculate percentage based on Relative Position in Visible Set
        const progress = (relativeActiveIndex / (visibleCount - 1)) * 100;
        progressBar.style.width = `${progress}%`;
      } else if (progressBar) {
         progressBar.style.width = '0%';
      }
    }

    // Initial Load
    updateActiveDot(0);
  }
};
