/**
 * Welcome Popups Controller
 * 
 * Features:
 * - Show popups only on first visit (localStorage)
 * - Sequential: Shipping Location -> Newsletter -> Done
 * - Save user preferences
 * - CF7 form integration ready
 */

class WelcomePopups {
	constructor(options = {}) {
		this.options = {
			storageKey: 'huyvo_welcome_shown',
			shippingStorageKey: 'huyvo_shipping_location',
			newsletterStorageKey: 'huyvo_newsletter_subscribed',
			showDelay: 1000, // Delay before showing first popup (ms)
			sequence: ['shipping', 'newsletter'], // Popup sequence
			...options
		};

		this.popups = {
			shipping: document.getElementById('popup-shipping'),
			newsletter: document.getElementById('popup-newsletter')
		};

		this.currentPopupIndex = 0;
		this.init();
	}

	init() {
		// Check if popups exist
		if (!this.popups.shipping && !this.popups.newsletter) {
			return;
		}

		// Bind events
		this.bindEvents();

		// Check if should show popups
		if (this.shouldShowPopups()) {
			setTimeout(() => {
				this.showNextPopup();
			}, this.options.showDelay);
		}
	}

	shouldShowPopups() {
		// Check sessionStorage
		const welcomeShown = sessionStorage.getItem(this.options.storageKey);
		return !welcomeShown;
	}

	bindEvents() {
		// Close buttons
		document.querySelectorAll('.popup-close').forEach(btn => {
			btn.addEventListener('click', (e) => {
				const popup = e.target.closest('.popup-overlay');
				this.closePopup(popup);
				this.markAsComplete();
				// Don't auto-show next popup when manually closing
			});
		});

		// Close on overlay click
		document.querySelectorAll('.popup-overlay').forEach(overlay => {
			overlay.addEventListener('click', (e) => {
				if (e.target === overlay) {
					this.closePopup(overlay);
					this.markAsComplete();
				}
			});
		});

		// Close on ESC
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				const activePopup = document.querySelector('.popup-overlay.is-active');
				if (activePopup) {
					this.closePopup(activePopup);
					this.markAsComplete();
				}
			}
		});

		// ========================================
		// POPUP TRIGGERS (<a> or <button> with data-popup-trigger)
		// Usage: <a href="#" data-popup-trigger="newsletter">Subscribe</a>
		//        <a href="#" data-popup-trigger="shipping">Change Location</a>
		// ========================================
		document.querySelectorAll('[data-popup-trigger]').forEach(trigger => {
			trigger.addEventListener('click', (e) => {
				e.preventDefault();
				const popupId = trigger.dataset.popupTrigger;
				this.showPopup(popupId);
			});
		});

		// Shipping form submit
		const shippingBtn = document.querySelector('[data-action="submit-shipping"]');
		if (shippingBtn) {
			shippingBtn.addEventListener('click', (e) => {
				e.preventDefault();
				this.handleShippingSubmit();
			});
		}

		// Newsletter form submit (CF7 compatible)
		const newsletterForm = document.querySelector('.popup-newsletter-form');
		if (newsletterForm) {
			newsletterForm.addEventListener('submit', (e) => {
				e.preventDefault();
				this.handleNewsletterSubmit(e);
			});
		}
	}

	showPopup(popupId) {
		const popup = this.popups[popupId];
		if (popup) {
			// Prevent body scroll
			document.body.style.overflow = 'hidden';
			
			popup.classList.add('is-active');

			// Dispatch custom event
			popup.dispatchEvent(new CustomEvent('popup:open', {
				detail: { popupId }
			}));
		}
	}

	closePopup(popup) {
		if (popup) {
			popup.classList.remove('is-active');

			// Restore body scroll if no active popups
			if (!document.querySelector('.popup-overlay.is-active')) {
				document.body.style.overflow = '';
			}

			// Dispatch custom event
			popup.dispatchEvent(new CustomEvent('popup:close', {
				detail: { popupId: popup.dataset.popup }
			}));
		}
	}

	showNextPopup() {
		const sequence = this.options.sequence;
		
		if (this.currentPopupIndex < sequence.length) {
			const nextPopupId = sequence[this.currentPopupIndex];
			this.currentPopupIndex++;
			
			// Small delay between popups
			setTimeout(() => {
				this.showPopup(nextPopupId);
			}, 300);
		} else {
			// All popups shown, mark as complete
			this.markAsComplete();
		}
	}

	handleShippingSubmit() {
		const country = document.getElementById('shipping-country')?.value;
		const language = document.getElementById('shipping-language')?.value;

		// Save to localStorage
		const shippingData = {
			country,
			language,
			timestamp: new Date().toISOString()
		};
		localStorage.setItem(this.options.shippingStorageKey, JSON.stringify(shippingData));

		// Dispatch custom event for WordPress/other integrations
		document.dispatchEvent(new CustomEvent('shipping:selected', {
			detail: shippingData
		}));

		console.log('Shipping location saved:', shippingData);

		// Close and show next
		this.closePopup(this.popups.shipping);
		this.showNextPopup();
	}

	handleNewsletterSubmit(e) {
		const form = e.target;
		const email = form.querySelector('input[type="email"]')?.value;
		const marketingConsent = form.querySelector('input[name="marketing-consent"]')?.checked;

		if (!email) {
			alert('Please enter your email address');
			return;
		}

		// For CF7 integration - let CF7 handle the actual submission
		// This is just for the popup flow
		const newsletterData = {
			email,
			marketingConsent,
			timestamp: new Date().toISOString()
		};

		// Save subscription status
		localStorage.setItem(this.options.newsletterStorageKey, JSON.stringify(newsletterData));

		// Dispatch custom event for WordPress/other integrations
		document.dispatchEvent(new CustomEvent('newsletter:subscribed', {
			detail: newsletterData
		}));

		console.log('Newsletter subscription:', newsletterData);

		// If CF7 is available, trigger its submission
		if (window.wpcf7 && form.classList.contains('wpcf7-form')) {
			// CF7 will handle form submission
			// Just close popup after a delay
			setTimeout(() => {
				this.closePopup(this.popups.newsletter);
				this.markAsComplete();
			}, 1000);
		} else {
			// Static form - close and complete
			this.closePopup(this.popups.newsletter);
			this.markAsComplete();
		}
	}

	markAsComplete() {
		sessionStorage.setItem(this.options.storageKey, JSON.stringify({
			shown: true,
			timestamp: new Date().toISOString()
		}));

		console.log('Welcome popups completed');
	}

	// Public methods for external control
	reset() {
		sessionStorage.removeItem(this.options.storageKey);
		localStorage.removeItem(this.options.shippingStorageKey);
		localStorage.removeItem(this.options.newsletterStorageKey);
		this.currentPopupIndex = 0;
		console.log('Welcome popups reset');
	}

	forceShow(popupId) {
		if (popupId && this.popups[popupId]) {
			this.showPopup(popupId);
		} else {
			// Show first popup
			this.currentPopupIndex = 0;
			this.showNextPopup();
		}
	}

	getShippingData() {
		const data = localStorage.getItem(this.options.shippingStorageKey);
		return data ? JSON.parse(data) : null;
	}

	isSubscribed() {
		return !!localStorage.getItem(this.options.newsletterStorageKey);
	}
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
	window.welcomePopups = new WelcomePopups();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
	module.exports = WelcomePopups;
}
