(function () {
	'use strict';

	function initCarousel(root) {
		var viewport = root.querySelector('[data-ms-carousel-viewport]');
		var track = root.querySelector('[data-ms-carousel-track]');
		var slides = track ? track.querySelectorAll('[data-ms-carousel-slide]') : [];
		var prev = root.querySelector('[data-ms-carousel-prev]');
		var next = root.querySelector('[data-ms-carousel-next]');
		var dotsWrap = root.querySelector('[data-ms-carousel-dots]');
		if (!viewport || !track || !slides.length) {
			return;
		}

		var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var dots = [];
		var index = 0;

		function slideStep() {
			var first = slides[0];
			if (!first) {
				return viewport.clientWidth;
			}
			var style = window.getComputedStyle(track);
			var gap = parseFloat(style.columnGap || style.gap) || 0;
			return first.getBoundingClientRect().width + gap;
		}

		function maxIndex() {
			var step = slideStep();
			if (!step) {
				return 0;
			}
			var extra = track.scrollWidth - viewport.clientWidth;
			return Math.max(0, Math.round(extra / step));
		}

		function goTo(i, smooth) {
			var max = maxIndex();
			index = Math.max(0, Math.min(i, max));
			viewport.scrollTo({
				left: index * slideStep(),
				behavior: !smooth || reduceMotion ? 'auto' : 'smooth',
			});
			dots.forEach(function (dot, di) {
				var current = di === index;
				dot.setAttribute('aria-current', current ? 'true' : 'false');
				dot.classList.toggle('is-active', current);
			});
			if (prev) {
				prev.disabled = index <= 0;
			}
			if (next) {
				next.disabled = index >= max;
			}
		}

		if (dotsWrap) {
			dotsWrap.innerHTML = '';
			var count = Math.max(1, maxIndex() + 1);
			for (var i = 0; i < count; i++) {
				(function (di) {
					var dot = document.createElement('button');
					dot.type = 'button';
					dot.className = 'ms-carousel__dot';
					dot.setAttribute('aria-label', 'Ir para o grupo ' + (di + 1));
					dot.addEventListener('click', function () {
						goTo(di, true);
					});
					dotsWrap.appendChild(dot);
					dots.push(dot);
				})(i);
			}
		}

		if (prev) {
			prev.addEventListener('click', function () {
				goTo(index - 1, true);
			});
		}
		if (next) {
			next.addEventListener('click', function () {
				goTo(index + 1, true);
			});
		}

		viewport.addEventListener(
			'scroll',
			function () {
				var step = slideStep();
				if (!step) {
					return;
				}
				var nextIndex = Math.round(viewport.scrollLeft / step);
				if (nextIndex !== index) {
					goTo(nextIndex, false);
				}
			},
			{ passive: true }
		);

		root.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft') {
				event.preventDefault();
				goTo(index - 1, true);
			} else if (event.key === 'ArrowRight') {
				event.preventDefault();
				goTo(index + 1, true);
			}
		});

		window.addEventListener('resize', function () {
			goTo(index, false);
		});

		track.querySelectorAll('img').forEach(function (img) {
			if (!img.complete) {
				img.addEventListener('load', function () {
					goTo(index, false);
				});
			}
		});

		goTo(0, false);
	}

	function ensureVideoPoster(video) {
		var slide = video.closest('[data-ms-rotator-slide]');
		if (!slide || slide.querySelector('.ms-video-poster')) {
			return;
		}

		function insertPoster(src) {
			if (!src || slide.querySelector('.ms-video-poster')) {
				return;
			}
			var img = document.createElement('img');
			img.className = 'ms-video-poster';
			img.src = src;
			img.alt = video.getAttribute('aria-label') || '';
			video.parentNode.insertBefore(img, video);
		}

		if (video.getAttribute('poster')) {
			insertPoster(video.getAttribute('poster'));
			return;
		}

		function grabFrame() {
			if (slide.querySelector('.ms-video-poster') || !video.videoWidth) {
				return;
			}
			try {
				var canvas = document.createElement('canvas');
				canvas.width = video.videoWidth;
				canvas.height = video.videoHeight;
				canvas.getContext('2d').drawImage(video, 0, 0);
				insertPoster(canvas.toDataURL('image/jpeg', 0.82));
			} catch (err) {}
			try {
				video.currentTime = 0;
			} catch (err) {}
		}

		function capture() {
			if (slide.querySelector('.ms-video-poster')) {
				return;
			}
			var onSeeked = function () {
				video.removeEventListener('seeked', onSeeked);
				grabFrame();
			};
			video.addEventListener('seeked', onSeeked);
			try {
				var stamp = 0.35;
				if (video.duration && isFinite(video.duration)) {
					stamp = Math.min(0.5, Math.max(0.1, video.duration * 0.05));
				}
				video.currentTime = stamp;
			} catch (err) {
				video.removeEventListener('seeked', onSeeked);
				grabFrame();
			}
		}

		if (video.readyState >= 2) {
			capture();
		} else {
			video.addEventListener('loadeddata', capture, { once: true });
		}
	}

	function videoSoundButton(video) {
		var slide = video.closest('[data-ms-rotator-slide]');
		return slide ? slide.querySelector('[data-ms-sound]') : null;
	}

	function syncVideoSound(video) {
		var btn = videoSoundButton(video);
		if (!btn) {
			return;
		}
		var on = !video.muted;
		btn.classList.toggle('is-muted', !on);
		btn.setAttribute('aria-pressed', on ? 'true' : 'false');
		btn.setAttribute('aria-label', on ? 'Desativar som' : 'Ativar som');
	}

	function bindVideoSound(video) {
		var btn = videoSoundButton(video);
		if (!btn || btn.getAttribute('data-ms-bound') === '1') {
			return;
		}
		btn.setAttribute('data-ms-bound', '1');
		video.muted = true;
		video.removeAttribute('data-ms-sound-on');
		syncVideoSound(video);
		btn.addEventListener('click', function (event) {
			event.preventDefault();
			event.stopPropagation();
			video.muted = !video.muted;
			if (video.muted) {
				video.removeAttribute('data-ms-sound-on');
			} else {
				video.volume = 1;
				video.setAttribute('data-ms-sound-on', '1');
				var play = video.play();
				if (play && play.catch) {
					play.catch(function () {});
				}
			}
			syncVideoSound(video);
		});
	}

	function initRotator(root) {
		var slides = root.querySelectorAll('[data-ms-rotator-slide]');
		var prev = root.querySelector('[data-ms-rotator-prev]');
		var next = root.querySelector('[data-ms-rotator-next]');
		if (!slides.length) {
			return;
		}

		var interval = parseInt(root.getAttribute('data-ms-interval') || '5000', 10);
		var hoverPlay = root.getAttribute('data-ms-play') === 'hover';
		var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var fineHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
		var index = 0;
		var timer = null;
		var videoHandler = null;
		var hovering = false;

		root.querySelectorAll('video').forEach(function (video) {
			ensureVideoPoster(video);
			bindVideoSound(video);
		});

		function pauseVideos(reset) {
			root.querySelectorAll('video').forEach(function (video) {
				video.pause();
				if (reset) {
					try {
						video.currentTime = 0;
					} catch (err) {}
				}
				if (videoHandler) {
					video.removeEventListener('ended', videoHandler);
				}
			});
			videoHandler = null;
			root.classList.remove('is-playing');
			slides.forEach(function (slide) {
				slide.classList.remove('is-playing');
			});
		}

		function setPlaying(on) {
			root.classList.toggle('is-playing', !!on);
			slides.forEach(function (slide, i) {
				slide.classList.toggle('is-playing', !!on && i === index);
			});
		}

		function playActive(reset) {
			var video = slides[index].querySelector('video');
			if (!video) {
				return null;
			}
			if (hoverPlay && !hovering) {
				return null;
			}
			video.muted = !video.hasAttribute('data-ms-sound-on');
			if (reset) {
				try {
					video.currentTime = 0;
				} catch (err) {}
			}
			if (hoverPlay && slides.length > 1) {
				videoHandler = function () {
					show(index + 1);
				};
				video.addEventListener('ended', videoHandler);
			}
			var play = video.play();
			if (play && play.then) {
				play.then(function () {
					setPlaying(true);
				}).catch(function () {
					video.muted = true;
					video.removeAttribute('data-ms-sound-on');
					syncVideoSound(video);
					var fallback = video.play();
					if (fallback && fallback.then) {
						fallback.then(function () {
							setPlaying(true);
						}).catch(function () {});
					} else {
						setPlaying(true);
					}
				});
			} else {
				setPlaying(true);
			}
			return video;
		}

		function stop() {
			if (timer) {
				clearTimeout(timer);
				timer = null;
			}
		}

		function armTimer() {
			stop();
			if (hoverPlay || reduceMotion || slides.length < 2) {
				return;
			}
			var video = slides[index].querySelector('video');
			var wait = interval;
			if (video) {
				wait = 20000;
				videoHandler = function () {
					show(index + 1);
				};
				video.addEventListener('ended', videoHandler);
			}
			timer = setTimeout(function () {
				show(index + 1);
			}, wait);
		}

		function show(next) {
			var max = slides.length;
			var target = ((next % max) + max) % max;
			pauseVideos(true);
			slides[index].classList.remove('is-active');
			index = target;
			slides[index].classList.add('is-active');
			playActive(true);
			armTimer();
		}

		if (prev) {
			prev.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				show(index - 1);
			});
		}
		if (next) {
			next.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				show(index + 1);
			});
		}

		if (hoverPlay) {
			root.addEventListener('mouseenter', function () {
				hovering = true;
				playActive(false);
			});
			root.addEventListener('mouseleave', function () {
				hovering = false;
				stop();
				pauseVideos(true);
			});
			if (!fineHover) {
				root.addEventListener('click', function (event) {
					if (event.target.closest('button')) {
						return;
					}
					hovering = !hovering;
					if (hovering) {
						playActive(false);
					} else {
						pauseVideos(true);
					}
				});
			}
		} else {
			root.addEventListener('mouseenter', stop);
			root.addEventListener('mouseleave', armTimer);
			root.addEventListener('focusin', stop);
			root.addEventListener('focusout', armTimer);
			playActive(true);
			armTimer();
		}

		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stop();
				pauseVideos();
			} else if (!hoverPlay) {
				playActive(false);
				armTimer();
			} else if (hovering) {
				playActive(false);
			}
		});
	}

	function zoomIcon() {
		return '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 16l5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';
	}

	function mediaFrom(el) {
		if (!el) {
			return null;
		}
		var video = el.tagName === 'VIDEO' ? el : el.querySelector ? el.querySelector('video') : null;
		if (video) {
			var source = video.querySelector('source');
			return {
				type: 'video',
				src: (source && source.getAttribute('src')) || video.currentSrc || video.src,
				poster: video.getAttribute('poster') || '',
				label: video.getAttribute('aria-label') || 'Vídeo',
			};
		}
		var img = el.tagName === 'IMG' ? el : el.querySelector ? el.querySelector('img') : null;
		if (img && img.getAttribute('src')) {
			return {
				type: 'image',
				src: img.currentSrc || img.getAttribute('src'),
				label: img.getAttribute('alt') || 'Foto',
			};
		}
		return null;
	}

	function closeZoom() {
		var box = document.querySelector('.ms-zoom');
		if (!box) {
			return;
		}
		var video = box.querySelector('video');
		if (video) {
			video.pause();
		}
		box.remove();
		document.documentElement.classList.remove('ms-zoom-open');
		document.removeEventListener('keydown', onZoomKey);
	}

	function onZoomKey(event) {
		if (event.key === 'Escape') {
			closeZoom();
		}
	}

	function openZoom(target) {
		var media = mediaFrom(target);
		if (!media || !media.src) {
			return;
		}
		closeZoom();
		var box = document.createElement('div');
		box.className = 'ms-zoom';
		box.setAttribute('role', 'dialog');
		box.setAttribute('aria-modal', 'true');
		box.setAttribute('aria-label', 'Zoom');
		var inner = '';
		if (media.type === 'video') {
			inner =
				'<video class="ms-zoom__media" controls playsinline autoplay' +
				(media.poster ? ' poster="' + media.poster.replace(/"/g, '') + '"' : '') +
				'><source src="' + media.src.replace(/"/g, '') + '"></video>';
		} else {
			inner = '<img class="ms-zoom__media" src="' + media.src.replace(/"/g, '') + '" alt="' + (media.label || '').replace(/"/g, '') + '">';
		}
		box.innerHTML =
			'<button type="button" class="ms-zoom__close" aria-label="Fechar zoom">×</button>' +
			'<div class="ms-zoom__stage">' +
			inner +
			'</div>';
		box.addEventListener('click', function (event) {
			if (event.target === box || event.target.classList.contains('ms-zoom__close')) {
				closeZoom();
			}
		});
		document.body.appendChild(box);
		document.documentElement.classList.add('ms-zoom-open');
		document.addEventListener('keydown', onZoomKey);
		var closeBtn = box.querySelector('.ms-zoom__close');
		if (closeBtn) {
			closeBtn.focus();
		}
	}

	function addZoomButton(host) {
		if (!host || host.querySelector('[data-ms-zoom]')) {
			return;
		}
		if (!mediaFrom(host)) {
			return;
		}
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'ms-zoom-btn';
		btn.setAttribute('data-ms-zoom', '1');
		btn.setAttribute('aria-label', 'Ampliar');
		btn.innerHTML = zoomIcon();
		btn.addEventListener('click', function (event) {
			event.preventDefault();
			event.stopPropagation();
			openZoom(host);
		});
		host.appendChild(btn);
	}

	function initMediaZoom() {
		document.querySelectorAll('[data-ms-rotator-slide]').forEach(addZoomButton);
		document.querySelectorAll('.ms-landing .ms-logo-panel:not([data-ms-rotator]), .ms-loja-strip > a, .ms-loja-strip__video').forEach(addZoomButton);
	}

	document.querySelectorAll('[data-ms-carousel]').forEach(initCarousel);
	document.querySelectorAll('[data-ms-rotator]').forEach(initRotator);
	initMediaZoom();
})();
