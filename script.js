document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.querySelector(".nav-toggle");
  const mobileMenu = document.getElementById("mobileMenu");
  const watchDemoBtn = document.getElementById("watch-demo");
  const featureSection = document.getElementById("solutions");
  const floatingCta = document.querySelector(".floating-cta");
  const leadForm = document.querySelector(".lead-form");

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

  if (leadForm) {
    const statusEl = leadForm.querySelector(".form-status");
    const submitBtn = leadForm.querySelector("button[type='submit']");

    const setStatus = (message = "", state = "") => {
      if (!statusEl) return;
      statusEl.textContent = message;
      statusEl.classList.remove("success", "error");
      if (state) statusEl.classList.add(state);
    };

    leadForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (submitBtn) submitBtn.disabled = true;
      setStatus("Enviando dados...");

      const formData = new FormData(leadForm);
      const payload = Object.fromEntries(formData.entries());

      try {
        const response = await fetch("/api/lead", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });

        if (!response.ok) {
          throw new Error(`Erro na API: ${response.status}`);
        }

        const data = await response.json();
        setStatus(data.message || "Recebemos seus dados!", "success");
        leadForm.reset();
      } catch (error) {
        console.error(error);
        setStatus("Não foi possível enviar agora. Tente novamente.", "error");
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  }

  watchDemoBtn.addEventListener("click", () => {
    featureSection.scrollIntoView({ behavior: "smooth" });
  });

  const applyBenefitTilt = () => {
    const solutionCards = document.querySelectorAll(".solution-card");
    const allowTilt = window.matchMedia("(pointer: fine)").matches;
    if (!allowTilt) return;

    solutionCards.forEach((card) => {
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
      ".solution-card, .testimonial, .section-header, .alliance-copy, .alliance-panel, .partner-footer, .contact-card, .contact-form"
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
