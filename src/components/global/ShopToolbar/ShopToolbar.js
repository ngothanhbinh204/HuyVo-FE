/**
 * Shop Toolbar - Filter Dropdown Controller
 * 
 * Features:
 * - Toggle dropdown open/close
 * - Select filter option
 * - Clear selection with X button
 * - Update URL params for WordPress compatibility
 * - Close dropdown on outside click
 */

class ShopToolbar {
	constructor() {
		this.dropdowns = document.querySelectorAll('.filter-dropdown');
		this.init();
	}

	init() {
		if (!this.dropdowns.length) return;

		this.dropdowns.forEach(dropdown => {
			this.initDropdown(dropdown);
		});

		// Close all dropdowns on outside click
		document.addEventListener('click', (e) => {
			if (!e.target.closest('.filter-dropdown')) {
				this.closeAllDropdowns();
			}
		});

		// Close on ESC key
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				this.closeAllDropdowns();
			}
		});

		// Initialize from URL params
		this.initFromURL();
	}

	initDropdown(dropdown) {
		const trigger = dropdown.querySelector('.filter-trigger');
		const clearBtn = dropdown.querySelector('.filter-clear');
		const options = dropdown.querySelectorAll('.filter-option a');

		// Toggle dropdown
		trigger.addEventListener('click', (e) => {
			// Don't toggle if clicking clear button
			if (e.target.closest('.filter-clear')) {
				e.stopPropagation();
				this.clearSelection(dropdown);
				return;
			}

			this.toggleDropdown(dropdown);
		});

		// Clear button click
		if (clearBtn) {
			clearBtn.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				this.clearSelection(dropdown);
			});
		}

		// Option selection
		options.forEach(option => {
			option.addEventListener('click', (e) => {
				e.preventDefault();
				this.selectOption(dropdown, option);
			});
		});
	}

	toggleDropdown(dropdown) {
		const isOpen = dropdown.classList.contains('is-open');

		// Close all other dropdowns
		this.closeAllDropdowns();

		// Toggle current
		if (!isOpen) {
			dropdown.classList.add('is-open');
		}
	}

	closeAllDropdowns() {
		this.dropdowns.forEach(d => d.classList.remove('is-open'));
	}

	selectOption(dropdown, optionLink) {
		const taxonomy = dropdown.dataset.taxonomy;
		const value = optionLink.dataset.filterValue;
		const label = optionLink.querySelector('.option-label').textContent;
		const triggerLabel = dropdown.querySelector('.filter-label');
		const optionItem = optionLink.closest('.filter-option');

		// Update UI
		dropdown.classList.add('has-value');
		dropdown.classList.remove('is-open');
		triggerLabel.textContent = label;

		// Mark option as selected
		dropdown.querySelectorAll('.filter-option').forEach(opt => {
			opt.classList.remove('is-selected');
		});
		optionItem.classList.add('is-selected');

		// Store selected value
		dropdown.dataset.selectedValue = value;

		// Update URL and trigger filter
		this.updateURL(taxonomy, value);
		this.triggerFilter();
	}

	clearSelection(dropdown) {
		const taxonomy = dropdown.dataset.taxonomy;
		const filterName = dropdown.querySelector('.filter-trigger').dataset.filterName;
		const triggerLabel = dropdown.querySelector('.filter-label');

		// Reset UI
		dropdown.classList.remove('has-value');
		dropdown.classList.remove('is-open');
		triggerLabel.textContent = filterName;

		// Clear selected option
		dropdown.querySelectorAll('.filter-option').forEach(opt => {
			opt.classList.remove('is-selected');
		});

		// Clear stored value
		delete dropdown.dataset.selectedValue;

		// Update URL and trigger filter
		this.updateURL(taxonomy, null);
		this.triggerFilter();
	}

	updateURL(taxonomy, value) {
		const url = new URL(window.location.href);

		if (value) {
			url.searchParams.set(taxonomy, value);
		} else {
			url.searchParams.delete(taxonomy);
		}

		// Update browser URL without reload (for AJAX filtering)
		window.history.replaceState({}, '', url.toString());
	}

	initFromURL() {
		const url = new URL(window.location.href);

		this.dropdowns.forEach(dropdown => {
			const taxonomy = dropdown.dataset.taxonomy;
			const value = url.searchParams.get(taxonomy);

			if (value) {
				// Find and select the option
				const option = dropdown.querySelector(`[data-filter-value="${value}"]`);
				if (option) {
					this.selectOption(dropdown, option);
				}
			}
		});
	}

	triggerFilter() {
		// Collect all active filters
		const activeFilters = {};

		this.dropdowns.forEach(dropdown => {
			const taxonomy = dropdown.dataset.taxonomy;
			const value = dropdown.dataset.selectedValue;

			if (value) {
				activeFilters[taxonomy] = value;
			}
		});

		// Dispatch custom event for WordPress AJAX integration
		const event = new CustomEvent('shopFilterChange', {
			detail: {
				filters: activeFilters,
				url: window.location.href
			}
		});
		document.dispatchEvent(event);

		// Log for debugging
		console.log('Active Filters:', activeFilters);
	}

	// Get current filters (for external use)
	getActiveFilters() {
		const filters = {};

		this.dropdowns.forEach(dropdown => {
			const taxonomy = dropdown.dataset.taxonomy;
			const value = dropdown.dataset.selectedValue;

			if (value) {
				filters[taxonomy] = value;
			}
		});

		return filters;
	}
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
	window.shopToolbar = new ShopToolbar();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
	module.exports = ShopToolbar;
}
