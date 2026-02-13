/**
 * Eleventh Set — Main JavaScript
 */
(function ($) {
  'use strict';

  /* ============================================================
     UTILS
  ============================================================ */
  const el = (selector, ctx = document) => ctx.querySelector(selector);
  const els = (selector, ctx = document) => [...ctx.querySelectorAll(selector)];

  /* ============================================================
     HEADER: Scroll behaviour (transparent → solid)
  ============================================================ */
  function initHeader() {
    const header = el('.site-header');
    if (!header) return;

    const isTransparent = header.classList.contains('header--transparent');
    if (!isTransparent) return;

    const threshold = 80;

    function updateHeader() {
      if (window.scrollY > threshold) {
        header.classList.add('header--scrolled');
        header.classList.remove('header--transparent');
      } else {
        header.classList.remove('header--scrolled');
        header.classList.add('header--transparent');
      }
    }

    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();
  }

  /* ============================================================
     MOBILE MENU
  ============================================================ */
  function initMobileMenu() {
    const toggle  = el('.js-mobile-toggle');
    const overlay = el('.js-mobile-nav');
    const body    = document.body;

    if (!toggle || !overlay) return;

    function openMenu() {
      toggle.classList.add('is-active');
      overlay.classList.add('is-open');
      body.classList.add('nav-open');
      toggle.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
      toggle.classList.remove('is-active');
      overlay.classList.remove('is-open');
      body.classList.remove('nav-open');
      toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', () => {
      if (overlay.classList.contains('is-open')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    // Close on link click
    els('a', overlay).forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeMenu();
    });
  }

  /* ============================================================
     PRODUCT GALLERY: Thumbnail switching
  ============================================================ */
  function initProductGallery() {
    const thumbs  = els('.js-gallery-thumb');
    const mainImg = el('#gallery-main-img');

    if (!thumbs.length || !mainImg) return;

    thumbs.forEach(thumb => {
      thumb.addEventListener('click', () => {
        // Update active state
        thumbs.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');

        // Swap main image
        const newSrc = thumb.dataset.full;
        if (newSrc) {
          mainImg.style.opacity = '0';
          mainImg.style.transition = 'opacity 0.2s ease';
          setTimeout(() => {
            mainImg.src = newSrc;
            mainImg.style.opacity = '1';
          }, 200);

          // Update lightbox link
          const lightboxLink = mainImg.closest('a[data-lightbox]');
          if (lightboxLink) {
            lightboxLink.href = thumb.dataset.src || newSrc;
          }
        }
      });
    });
  }

  /* ============================================================
     QUANTITY SELECTOR
  ============================================================ */
  function initQuantitySelectors() {
    els('.quantity-selector, .product-add-to-cart').forEach(wrapper => {
      const minusBtn = el('.js-qty-minus', wrapper);
      const plusBtn  = el('.js-qty-plus', wrapper);
      const input    = el('.js-qty-input', wrapper);

      if (!minusBtn || !plusBtn || !input) return;

      minusBtn.addEventListener('click', () => {
        const val = parseInt(input.value, 10) || 1;
        const min = parseInt(input.min, 10) || 1;
        if (val > min) input.value = val - 1;
      });

      plusBtn.addEventListener('click', () => {
        const val = parseInt(input.value, 10) || 1;
        const max = parseInt(input.max, 10) || 999;
        if (val < max) input.value = val + 1;
      });

      input.addEventListener('change', () => {
        const val = parseInt(input.value, 10) || 1;
        const min = parseInt(input.min, 10) || 1;
        const max = parseInt(input.max, 10) || 999;
        input.value = Math.max(min, Math.min(max, val));
      });
    });
  }

  /* ============================================================
     PRODUCT VARIATION SWATCHES (Color & Size)
  ============================================================ */
  function initVariationSwatches() {
    const form = el('.js-variation-form');
    if (!form) return;

    const variationData  = JSON.parse(form.dataset.product_variations || '[]');
    const variationIdInput = el('.js-variation-id', form);
    const stockNotice    = el('.js-stock-notice', form);
    const addToCartBtn   = el('.js-add-to-cart', form);

    let selectedAttrs = {};

    // Color Swatches
    els('.js-color-swatch', form).forEach(swatch => {
      swatch.addEventListener('click', () => {
        const attr  = swatch.dataset.attr;
        const value = swatch.dataset.value;

        // Deactivate siblings
        els(`.js-color-swatch[data-attr="${attr}"]`, form).forEach(s => {
          s.classList.remove('active');
          s.setAttribute('aria-checked', 'false');
        });

        swatch.classList.add('active');
        swatch.setAttribute('aria-checked', 'true');
        selectedAttrs[attr] = value;

        // Update label
        const label = el(`.js-selected-${sanitizeAttrName(attr)}`, form);
        if (label) label.textContent = value;

        updateAttrInput(form, attr, value);
        matchVariation(variationData, selectedAttrs, variationIdInput, stockNotice, addToCartBtn);
      });
    });

    // Size Buttons
    els('.js-size-btn', form).forEach(btn => {
      btn.addEventListener('click', () => {
        const attr  = btn.dataset.attr;
        const value = btn.dataset.value;

        els(`.js-size-btn[data-attr="${attr}"]`, form).forEach(b => {
          b.classList.remove('active');
          b.setAttribute('aria-checked', 'false');
        });

        btn.classList.add('active');
        btn.setAttribute('aria-checked', 'true');
        selectedAttrs[attr] = value;

        const label = el(`.js-selected-${sanitizeAttrName(attr)}`, form);
        if (label) label.textContent = value;

        updateAttrInput(form, attr, value);
        matchVariation(variationData, selectedAttrs, variationIdInput, stockNotice, addToCartBtn);
      });
    });

    // Standard selects
    els('.js-variation-select', form).forEach(select => {
      select.addEventListener('change', () => {
        const attr  = select.dataset.attr;
        const value = select.value;
        selectedAttrs[attr] = value;
        updateAttrInput(form, attr, value);
        matchVariation(variationData, selectedAttrs, variationIdInput, stockNotice, addToCartBtn);
      });
    });
  }

  function sanitizeAttrName(name) {
    return name.toLowerCase().replace(/[^a-z0-9]/g, '-');
  }

  function updateAttrInput(form, attr, value) {
    const input = el(`.js-attr-input[data-attr="${attr}"]`, form);
    if (input) input.value = value;
  }

  function matchVariation(variations, selected, variationIdInput, stockNotice, addToCartBtn) {
    if (!variationIdInput) return;

    const match = variations.find(variation => {
      return Object.entries(variation.attributes).every(([key, val]) => {
        const attrKey = key.replace('attribute_', '');
        return !val || selected[attrKey] === val || selected[key] === val;
      });
    });

    if (match) {
      variationIdInput.value = match.variation_id;
      if (stockNotice) {
        stockNotice.style.display = match.is_in_stock ? 'none' : 'block';
      }
      if (addToCartBtn) {
        addToCartBtn.disabled = !match.is_in_stock;
        addToCartBtn.textContent = match.is_in_stock
          ? addToCartBtn.dataset.textAdd || 'Add to Cart'
          : 'Out of Stock';
      }
    } else {
      variationIdInput.value = 0;
    }
  }

  /* ============================================================
     SIMPLE PRODUCT: AJAX Add to Cart
  ============================================================ */
  function initSimpleAddToCart() {
    els('.js-simple-add-to-cart').forEach(btn => {
      btn.addEventListener('click', function () {
        const productId = this.dataset.productId;
        const qty = el('.js-qty-input');
        const quantity = qty ? parseInt(qty.value, 10) : 1;

        if (!productId || !window.eleventhsetData) return;

        btn.textContent = 'Adding...';
        btn.disabled = true;

        $.ajax({
          url: window.eleventhsetData.ajaxUrl,
          method: 'POST',
          data: {
            action: 'eleventhset_add_to_cart',
            nonce: window.eleventhsetData.nonce,
            product_id: productId,
            quantity: quantity,
          },
          success: function (res) {
            if (res.success) {
              btn.textContent = 'Added!';
              updateCartCount(res.data.cart_count);
              setTimeout(() => {
                btn.textContent = 'Add to Cart';
                btn.disabled = false;
              }, 2000);
            } else {
              btn.textContent = res.data || 'Error';
              btn.disabled = false;
            }
          },
          error: function () {
            btn.textContent = 'Error — Try Again';
            btn.disabled = false;
          }
        });
      });
    });
  }

  function updateCartCount(count) {
    const badge = el('.js-cart-count');
    if (!badge) return;
    if (count > 0) {
      badge.textContent = count;
      badge.style.display = 'flex';
    } else {
      badge.style.display = 'none';
    }
  }

  /* ============================================================
     ACCORDION
  ============================================================ */
  function initAccordions() {
    els('.js-accordion-toggle').forEach(header => {
      header.addEventListener('click', () => {
        const item = header.closest('.accordion-item');
        if (!item) return;
        const isOpen = item.classList.contains('is-open');

        // Close all
        els('.accordion-item').forEach(i => i.classList.remove('is-open'));

        // Toggle clicked
        if (!isOpen) {
          item.classList.add('is-open');
        }
      });
    });

    // Open first by default on product page
    const firstItem = el('.product-accordion .accordion-item');
    if (firstItem) firstItem.classList.add('is-open');
  }

  /* ============================================================
     CONTACT FORM (AJAX)
  ============================================================ */
  function initContactForm() {
    const form = el('#eleventhset-contact-form');
    if (!form || !window.eleventhsetData) return;

    const submitBtn   = el('#contact-submit-btn', form);
    const btnText     = el('.btn-text', submitBtn);
    const btnLoading  = el('.btn-loading', submitBtn);
    const responseDiv = el('.js-form-response', form);

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      // Loading state
      submitBtn.disabled = true;
      if (btnText) btnText.style.display = 'none';
      if (btnLoading) btnLoading.style.display = 'inline';
      if (responseDiv) responseDiv.style.display = 'none';

      const nonce = el('[name="nonce"]', form);

      $.ajax({
        url: window.eleventhsetData.ajaxUrl,
        method: 'POST',
        data: {
          action: 'eleventhset_contact',
          nonce: nonce ? nonce.value : '',
          contact_name:    el('[name="contact_name"]', form).value,
          contact_email:   el('[name="contact_email"]', form).value,
          contact_subject: el('[name="contact_subject"]', form).value,
          contact_message: el('[name="contact_message"]', form).value,
        },
        success: function (res) {
          if (res.success) {
            showFormMessage(responseDiv, res.data, 'success');
            form.reset();
          } else {
            showFormMessage(responseDiv, res.data || 'Something went wrong.', 'error');
          }
        },
        error: function () {
          showFormMessage(responseDiv, 'Network error. Please try again.', 'error');
        },
        complete: function () {
          submitBtn.disabled = false;
          if (btnText) btnText.style.display = 'inline';
          if (btnLoading) btnLoading.style.display = 'none';
        }
      });
    });
  }

  function showFormMessage(el, message, type) {
    if (!el) return;
    el.textContent = message;
    el.style.display = 'block';
    el.style.borderLeft = `3px solid ${type === 'success' ? 'var(--color-success)' : 'var(--color-error)'}`;
    el.style.backgroundColor = type === 'success' ? 'rgba(39,174,96,0.08)' : 'rgba(192,57,43,0.08)';
  }

  /* ============================================================
     NEWSLETTER FORM
  ============================================================ */
  function initNewsletterForm() {
    const form = el('.js-newsletter-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const emailInput = el('[name="email"]', form);
      const btn = el('button[type="submit"]', form);

      if (!emailInput || !emailInput.value) return;

      btn.textContent = '✓ Subscribed!';
      btn.style.backgroundColor = 'var(--color-success)';
      emailInput.value = '';

      setTimeout(() => {
        btn.textContent = 'Subscribe';
        btn.style.backgroundColor = '';
      }, 3000);
    });
  }

  /* ============================================================
     SMOOTH REVEAL ANIMATIONS (Intersection Observer)
  ============================================================ */
  function initRevealAnimations() {
    if (!('IntersectionObserver' in window)) return;

    const style = document.createElement('style');
    style.textContent = `
      .reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.6s ease, transform 0.6s ease;
      }
      .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
      }
      .reveal-delay-1 { transition-delay: 0.1s; }
      .reveal-delay-2 { transition-delay: 0.2s; }
      .reveal-delay-3 { transition-delay: 0.3s; }
    `;
    document.head.appendChild(style);

    // Add reveal class to target elements
    els('.product-card, .value-card, .team-card, .section-header, .about-story__content, .contact-info, .contact-form-wrapper').forEach((el, i) => {
      el.classList.add('reveal');
      if (i % 4 === 1) el.classList.add('reveal-delay-1');
      if (i % 4 === 2) el.classList.add('reveal-delay-2');
      if (i % 4 === 3) el.classList.add('reveal-delay-3');
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -40px 0px',
    });

    els('.reveal').forEach(el => observer.observe(el));
  }

  /* ============================================================
     WOOCOMMERCE CART COUNT UPDATE
  ============================================================ */
  function initCartCountUpdate() {
    $(document.body).on('wc_fragments_refreshed wc_fragments_loaded added_to_cart', function () {
      const countEl = el('.woocommerce-cart-form') || el('.cart-contents');
      // Refresh cart fragments via WooCommerce
      if (window.wc_cart_fragments_params) {
        // WC handles this automatically via AJAX
      }
    });
  }

  /* ============================================================
     INIT
  ============================================================ */
  function init() {
    initHeader();
    initMobileMenu();
    initProductGallery();
    initQuantitySelectors();
    initVariationSwatches();
    initSimpleAddToCart();
    initAccordions();
    initContactForm();
    initNewsletterForm();
    initRevealAnimations();
    initCartCountUpdate();
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

}(window.jQuery || { ajax: () => {}, fn: {} }));
