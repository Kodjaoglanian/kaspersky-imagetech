document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.querySelector(".nav-toggle");
  const mobileMenu = document.getElementById("mobileMenu");
  const watchDemoBtn = document.getElementById("watch-demo");
  const featureSection = document.getElementById("features");
  const floatingCta = document.querySelector(".floating-cta");

  const toggleMobileMenu = () => {
    const isOpen = mobileMenu.classList.toggle("open");
    navToggle.setAttribute("aria-expanded", String(isOpen));
  };

  navToggle.addEventListener("click", () => {
    toggleMobileMenu();
  });

  document.addEventListener("click", (event) => {
    if (!mobileMenu.classList.contains("open")) return;
    const clickInsideMenu = mobileMenu.contains(event.target);
    const clickOnToggle = event.target === navToggle || navToggle.contains(event.target);
    if (!clickInsideMenu && !clickOnToggle) {
      mobileMenu.classList.remove("open");
      navToggle.setAttribute("aria-expanded", "false");
    }
  });

  const mq = window.matchMedia("(min-width: 901px)");
  const handleMediaChange = (event) => {
    if (event.matches) {
      mobileMenu.classList.remove("open");
      navToggle.setAttribute("aria-expanded", "false");
    }
  };
  if (typeof mq.addEventListener === "function") {
    mq.addEventListener("change", handleMediaChange);
  } else if (typeof mq.addListener === "function") {
    mq.addListener(handleMediaChange);
  }

  mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      mobileMenu.classList.remove("open");
      navToggle.setAttribute("aria-expanded", "false");
    });
  });

  const toggleFloatingCta = () => {
    if (!floatingCta) return;
    if (window.scrollY > 400) {
      floatingCta.classList.add("show");
    } else {
      floatingCta.classList.remove("show");
    }
  };

  window.addEventListener("scroll", toggleFloatingCta, { passive: true });
  toggleFloatingCta();

  watchDemoBtn.addEventListener("click", () => {
    featureSection.scrollIntoView({ behavior: "smooth" });
  });

  const applyBenefitTilt = () => {
    const benefitCards = document.querySelectorAll(".benefit-card");
    const allowTilt = window.matchMedia("(pointer: fine)").matches;
    if (!allowTilt) return;

    benefitCards.forEach((card) => {
      card.addEventListener("mousemove", (event) => {
        const rect = card.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
        const percentX = x / rect.width;
        const percentY = y / rect.height;
        const rotateX = (percentY - 0.5) * -12;
        const rotateY = (percentX - 0.5) * 12;
        card.style.setProperty("--card-rotate-x", `${rotateX}deg`);
        card.style.setProperty("--card-rotate-y", `${rotateY}deg`);
        card.style.setProperty("--card-glow-x", `${percentX * 100}%`);
        card.style.setProperty("--card-glow-y", `${percentY * 100}%`);
      });

      card.addEventListener("mouseleave", () => {
        card.style.setProperty("--card-rotate-x", "0deg");
        card.style.setProperty("--card-rotate-y", "0deg");
        card.style.setProperty("--card-glow-x", "50%");
        card.style.setProperty("--card-glow-y", "15%");
      });
    });
  };

  const initializeGsapAnimations = () => {
    if (!window.gsap) {
      console.warn("GSAP não foi carregado; animações serão reduzidas.");
      return;
    }

    const heroTl = gsap.timeline({ defaults: { duration: 0.9, ease: "power2.out" } });
    heroTl
      .from(".nav", { y: -20, opacity: 0 })
      .from(".hero-text .eyebrow", { y: 30, opacity: 0 }, "-=0.4")
      .from(".hero-text h1", { y: 30, opacity: 0 }, "-=0.35")
      .from(".hero-text p", { y: 30, opacity: 0 }, "-=0.3")
      .from(".hero-actions", { y: 30, opacity: 0 }, "-=0.25")
      .from(".hero-stats > div", { y: 30, opacity: 0, stagger: 0.12 }, "-=0.2")
      .from(".hero-visual", { x: 40, opacity: 0 }, "-=0.8");

    gsap.to(".hero-card", {
      y: -10,
      duration: 2.6,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
    });

    gsap.to(".hero-glow", {
      scale: 1.08,
      rotate: 6,
      y: -20,
      duration: 8,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
    });

    gsap.to(".hero-grid", {
      backgroundPosition: "180px 160px",
      duration: 18,
      repeat: -1,
      ease: "none",
    });

    const revealTargets = document.querySelectorAll(
      ".benefit-card, .feature, .testimonial, .timeline-item, .section-header, .alliance-intro, .ally-card, .partner-footer, .contact-card, .contact-form, .presence-card, .presence-metric, .faq-item"
    );

    if ("IntersectionObserver" in window) {
      const revealObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const delay = parseFloat(entry.target.dataset.delay || "0");
            gsap.to(entry.target, {
              opacity: 1,
              y: 0,
              duration: 1,
              delay,
              ease: "power3.out",
            });
            revealObserver.unobserve(entry.target);
          });
        },
        { threshold: 0.25, rootMargin: "0px 0px -10% 0px" }
      );

      revealTargets.forEach((el) => {
        if (el.closest(".hero")) return;
        el.style.opacity = "0";
        el.style.transform = "translateY(60px)";
        revealObserver.observe(el);
      });
    }
  };

  applyBenefitTilt();
  initializeGsapAnimations();
});
