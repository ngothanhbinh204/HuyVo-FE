export const initHome4 = () => {
	const descWrapper = document.querySelector(".section-home-popular .section-desc-wrapper");
	const readMoreBtn = document.querySelector(".section-home-popular .btn-readmore");
	
	if (descWrapper && readMoreBtn) {
		readMoreBtn.addEventListener("click", function(e) {
			e.preventDefault();
			
			const isExpanded = descWrapper.classList.contains("is-expanded");
			
			if (isExpanded) {
				// Collapse
				descWrapper.classList.remove("is-expanded");
				this.classList.remove("active");
				// Need to reset max-height to styling default via class removal
				descWrapper.style.maxHeight = null; 
				this.querySelector("span").textContent = "Read more";
			} else {
				// Expand
				descWrapper.classList.add("is-expanded");
				this.classList.add("active");
				// distinct height for nice transition if we want exact height animation from JS
				descWrapper.style.maxHeight = descWrapper.scrollHeight + "px";
				this.querySelector("span").textContent = "Show less";
			}
		});
	}
};
