import Swiper from "swiper";
import { FreeMode, Navigation, Thumbs, Controller, Pagination } from "swiper/modules";

/**
 * Product Media Scroll & Drag Logic (Desktop)
 */
export function initProductMediaScroll() {
    const elements = {
      container: document.querySelector('.product-media'),
      thumbParent: document.getElementById('mediaThumbDragParent'),
      cursor: document.getElementById('mediaThumbDragBox')
    };
  
    if (!elements.container || !elements.thumbParent || !elements.cursor) return;
    
    // Desktop only guard for this logic
    if (window.innerWidth < 1024) return;
  
    let state = {
      isDragging: false,
      startY: 0,
      startCursorTop: 0,
      rafId: null
    };
  
    const syncCursorToScroll = () => {
      // Check again inside frame if still desktop
      if (window.innerWidth < 1024) return;
      if (state.isDragging) return;
  
      const rect = elements.container.getBoundingClientRect();
      const viewportHeight = window.innerHeight;
      const offsetTop = 100; // Header/Sticky buffer
      
      const containerHeight = elements.container.offsetHeight;
      const scrollableDist = containerHeight - viewportHeight + offsetTop;
      const currentScrolled = offsetTop - rect.top;
      
      let percent = currentScrolled / scrollableDist;
      percent = Math.max(0, Math.min(1, percent));
  
      const trackHeight = elements.thumbParent.offsetHeight;
      const cursorHeight = elements.cursor.offsetHeight;
      const maxCursorTop = trackHeight - cursorHeight;
  
      const newTop = percent * maxCursorTop;
      elements.cursor.style.top = `${newTop}px`;
    };
  
    const onDragStart = (e) => {
      if (window.innerWidth < 1024) return;
      e.preventDefault();
      state.isDragging = true;
      state.startY = e.clientY || e.touches?.[0]?.clientY;
      state.startCursorTop = parseFloat(elements.cursor.style.top) || 0;
      elements.cursor.style.cursor = 'grabbing';
      document.body.style.userSelect = 'none';
      document.addEventListener('mousemove', onDragMove);
      document.addEventListener('mouseup', onDragEnd);
      document.addEventListener('touchmove', onDragMove, { passive: false });
      document.addEventListener('touchend', onDragEnd);
    };
  
    const onDragMove = (e) => {
      if (!state.isDragging) return;
      e.preventDefault();
      const clientY = e.clientY || e.touches?.[0]?.clientY;
      const deltaY = clientY - state.startY;
      
      const trackHeight = elements.thumbParent.offsetHeight;
      const cursorHeight = elements.cursor.offsetHeight;
      const maxCursorTop = trackHeight - cursorHeight;
      let newTop = state.startCursorTop + deltaY;
      newTop = Math.max(0, Math.min(maxCursorTop, newTop));
      elements.cursor.style.top = `${newTop}px`;
      const percent = newTop / maxCursorTop;
      const rect = elements.container.getBoundingClientRect();
      const containerCurrentTop = rect.top + window.scrollY;
      const viewportHeight = window.innerHeight;
      const offsetTop = 100;
      const scrollableDist = elements.container.offsetHeight - viewportHeight + offsetTop;
      const targetScrollY = containerCurrentTop + (percent * scrollableDist) - offsetTop;
      window.scrollTo(0, targetScrollY);
    };
  
    const onDragEnd = () => {
      state.isDragging = false;
      elements.cursor.style.cursor = '';
      document.body.style.userSelect = '';
      document.removeEventListener('mousemove', onDragMove);
      document.removeEventListener('mouseup', onDragEnd);
      document.removeEventListener('touchmove', onDragMove);
      document.removeEventListener('touchend', onDragEnd);
    };
  
    const onDblClick = (e) => {
      if (window.innerWidth < 1024) return;
      if (elements.cursor.contains(e.target)) return;
      const rect = elements.thumbParent.getBoundingClientRect();
      const clickY = e.clientY - rect.top;
      const trackHeight = elements.thumbParent.offsetHeight;
      const cursorHeight = elements.cursor.offsetHeight;
      const maxCursorTop = trackHeight - cursorHeight;
      let targetTop = clickY - (cursorHeight / 2);
      targetTop = Math.max(0, Math.min(maxCursorTop, targetTop));
      const percent = targetTop / maxCursorTop;
      const containerRect = elements.container.getBoundingClientRect();
      const containerCurrentTop = containerRect.top + window.scrollY;
      const viewportHeight = window.innerHeight;
      const offsetTop = 100;
      const scrollableDist = elements.container.offsetHeight - viewportHeight + offsetTop;
      const targetScrollY = containerCurrentTop + (percent * scrollableDist) - offsetTop;
      window.scrollTo({
        top: targetScrollY,
        behavior: 'smooth'
      });
    };
  
    window.addEventListener('scroll', () => {
      if (!state.rafId) {
        state.rafId = requestAnimationFrame(() => {
          syncCursorToScroll();
          state.rafId = null;
        });
      }
    });

    elements.cursor.addEventListener('mousedown', onDragStart);
    elements.cursor.addEventListener('touchstart', onDragStart, { passive: false });
    elements.thumbParent.addEventListener('dblclick', onDblClick);
    
    // Initial sync check
    syncCursorToScroll();
}

/**
 * Mobile Media Swiper Logic
 */
export function initProductMediaSliderMobile() {
    let mainSwiper = null;
    let thumbSwiper = null;

    const initSwiper = () => {
        if (window.innerWidth >= 1024) {
             if (mainSwiper) {
                mainSwiper.destroy(true, true);
                mainSwiper = null;
             }
             if (thumbSwiper) {
                thumbSwiper.destroy(true, true);
                thumbSwiper = null;
             }
             return;
        }

        if (mainSwiper) return; // Already init

        // Init Thumbs
        thumbSwiper = new Swiper('.product-thumbs', {
            modules: [FreeMode],
            spaceBetween: 10,
            slidesPerView: 'auto',
            freeMode: true,
            watchSlidesProgress: true,
        });

        // Init Main
        mainSwiper = new Swiper('.product-images', {
            modules: [Thumbs, Navigation],
            spaceBetween: 10,
            slidesPerView: 1,
            thumbs: {
                swiper: thumbSwiper,
            },
        });
    }

    // Init on load
    initSwiper();

    // Check on resize
    window.addEventListener('resize', () => {
        // Simple debounce or direct check
        initSwiper(); 
        
        // Also re-trigger desktop scroll init check if switched to desktop
        if (window.innerWidth >= 1024) {
            initProductMediaScroll(); 
        }
    });
}

/**
 * Size Guide Popup Logic
 */
export function initSizeGuidePopup() {
    const trigger = document.querySelector('.size-guide-link');
    const popup = document.getElementById('popupSizeGuide');
    if (!trigger || !popup) return;

    const closeBtn = popup.querySelector('.popup-close');
    const backdrop = popup.querySelector('.popup-backdrop');
    const tabs = popup.querySelectorAll('.tab-item');
    const tabContents = popup.querySelectorAll('.tab-content'); // Should check correct class or attribute

    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        popup.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    const closePopup = () => {
        popup.classList.remove('active');
        document.body.style.overflow = '';
    };

    closeBtn.addEventListener('click', closePopup);
    backdrop.addEventListener('click', closePopup);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && popup.classList.contains('active')) closePopup();
    });

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            // Fix: select popup-tab-content instead of generic tab-content if reused
            const popupContents = popup.querySelectorAll('.popup-tab-content');
            popupContents.forEach(c => c.classList.remove('active'));

            tab.classList.add('active');
            const targetId = tab.getAttribute('data-tab');
            const targetContent = document.getElementById(targetId);
            if (targetContent) targetContent.classList.add('active');
        });
    });
}
