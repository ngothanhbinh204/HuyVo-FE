// ================================================
// HERO BANNER MODULE
// Video autoplay with sound toggle + poster fallback
// ================================================

export const heroBanner = {
	init: () => {
		const bannerSection = document.querySelector(".section-home-banner");
		if (!bannerSection) return;

		const bannerMedia = bannerSection.querySelector(".banner-media");
		const video = bannerSection.querySelector(".banner-video");
		const soundToggle = bannerSection.querySelector(".sound-toggle");
		const poster = bannerSection.querySelector(".banner-poster");

		if (!video) return;

		// ----------------------------------------
		// Video Play Detection
		// Ẩn poster khi video bắt đầu play
		// ----------------------------------------
		const handleVideoPlay = () => {
			bannerMedia?.classList.add("video-playing");
		};

		const handleVideoCanPlay = () => {
			// Video đã sẵn sàng play
			bannerMedia?.classList.add("video-ready");
		};

		video.addEventListener("playing", handleVideoPlay);
		video.addEventListener("canplay", handleVideoCanPlay);

		// Fallback: nếu video đã playing khi script load
		if (!video.paused) {
			handleVideoPlay();
		}

		// ----------------------------------------
		// Sound Toggle
		// ----------------------------------------
		if (soundToggle) {
			soundToggle.addEventListener("click", () => {
				if (video.muted) {
					video.muted = false;
					soundToggle.classList.add("active");
				} else {
					video.muted = true;
					soundToggle.classList.remove("active");
				}
			});
		}

		// ----------------------------------------
		// Mobile Autoplay Fallback
		// Một số mobile không cho autoplay, thử play manually
		// ----------------------------------------
		const attemptAutoplay = async () => {
			try {
				await video.play();
			} catch (error) {
				// Autoplay bị chặn, poster sẽ hiển thị
				console.log("Autoplay blocked, showing poster fallback");
				bannerMedia?.classList.add("autoplay-blocked");
			}
		};

		// Thử autoplay nếu video chưa play
		if (video.paused) {
			attemptAutoplay();
		}

		// ----------------------------------------
		// Pause video khi không trong viewport (tiết kiệm tài nguyên)
		// ----------------------------------------
		const observerOptions = {
			root: null,
			rootMargin: "0px",
			threshold: 0.1,
		};

		const handleIntersection = (entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					// Video trong viewport - play
					if (video.paused) {
						video.play().catch(() => {});
					}
				} else {
					// Video ngoài viewport - pause
					if (!video.paused) {
						video.pause();
					}
				}
			});
		};

		const observer = new IntersectionObserver(
			handleIntersection,
			observerOptions
		);
		observer.observe(bannerSection);

		// Cleanup khi component bị destroy (SPA)
		return () => {
			observer.disconnect();
			video.removeEventListener("playing", handleVideoPlay);
			video.removeEventListener("canplay", handleVideoCanPlay);
		};
	},
};
