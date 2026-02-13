# Eleventh Set WordPress Theme — Deployment Guide

## Overview

This repository contains a complete custom WordPress theme for **Eleventh Set** — a minimalist clothing brand store. The theme recreates the eleventhset.com design with:

- Cormorant Garamond (serif) + Montserrat (sans-serif) typography
- Black, white, off-white and warm gold (#c9a96e) color palette
- Full-screen hero with transparent-to-solid navigation
- WooCommerce integration with color/size swatches
- Mobile-responsive design
- Contact form with AJAX submission
- Custom product gallery with thumbnail switching

---

## Deployment Steps

### 1. Upload Theme to WordPress

**Via cPanel / File Manager:**
1. Zip the `theme/eleventhset/` folder → `eleventhset.zip`
2. In Hostinger cPanel → File Manager → navigate to `/public_html/wp-content/themes/`
3. Upload and extract `eleventhset.zip`

**Via FTP (FileZilla):**
1. Connect to your Hostinger FTP (credentials in Hostinger panel)
2. Upload the `theme/eleventhset/` folder to `/public_html/wp-content/themes/eleventhset/`

**Via WP Admin:**
1. WP Admin → Appearance → Themes → Add New → Upload Theme
2. Upload the zipped theme folder
3. Activate

### 2. Activate Theme

1. Go to **WP Admin → Appearance → Themes**
2. Find "Eleventh Set" and click **Activate**

### 3. Run Setup Wizard

1. Go to **WP Admin → Appearance → Theme Setup**
2. Click **"Run Setup Now"**

This will automatically:
- Create all pages (Home, About, Contact, Shop, Cart, Checkout, My Account, FAQ, Shipping, Size Guide)
- Create and assign the primary navigation menu
- Configure WooCommerce pages and settings
- Create product categories (Women, Men, Tops, Bottoms, Outerwear, Dresses, Accessories)
- Set the static homepage

### 4. Install Required Plugins

Go to **WP Admin → Plugins → Add New** and install:

| Plugin | Purpose | Required? |
|--------|---------|-----------|
| WooCommerce | E-commerce | **Yes** |
| Contact Form 7 | Advanced contact forms | Optional |
| WooCommerce Variation Swatches | Visual color/size selectors | Recommended |
| Yoast SEO | SEO optimization | Recommended |
| WP Super Cache | Performance | Recommended |

### 5. Configure Customizer

Go to **WP Admin → Appearance → Customize → Eleventh Set Options**:

- **Homepage Hero**: Set hero eyebrow text, heading, subtitle, and background image
- **Brand Colors**: Customize accent color (default: #c9a96e golden/warm tone)
- **Contact Information**: Email, phone, address, hours, social media URLs

### 6. Set Up Logo

1. **WP Admin → Appearance → Customize → Site Identity**
2. Upload your logo (recommended: 280×80px, transparent PNG)
3. Set site title to "Eleventh Set"

### 7. Add Products

Create 5–10 clothing products with WooCommerce:

1. **WP Admin → Products → Add New**
2. Set product as **Variable Product**
3. Under "Attributes": Add `Color` and `Size` attributes
   - Color values: Black, White, Cream, Beige, Navy, Sage, etc.
   - Size values: XS, S, M, L, XL, XXL
4. Under "Variations": Click "Generate All Variations"
5. Set prices, stock, and images per variation
6. Add 4–6 product images (main + gallery)
7. Write a short description and full description

**Recommended Product Structure:**
```
Products (5-10 items):
├── Linen Shirt        [Colors: White, Black, Sage]    [Sizes: XS-XL]
├── Wide Leg Trousers  [Colors: Black, Cream, Navy]    [Sizes: XS-XL]
├── Relaxed Blazer     [Colors: Black, Beige, Charcoal][Sizes: XS-XL]
├── Slip Dress         [Colors: Black, Ivory, Burgundy][Sizes: XS-XL]
├── Cotton Tee         [Colors: White, Black, Stone]   [Sizes: XS-XXL]
├── Wrap Midi Skirt    [Colors: Sage, Black, Beige]    [Sizes: XS-XL]
├── Trench Coat        [Colors: Camel, Black]          [Sizes: XS-XL]
└── Cashmere Knit      [Colors: Cream, Charcoal, Blush][Sizes: XS-XL]
```

### 8. Configure WooCommerce Settings

**WP Admin → WooCommerce → Settings:**

- **General**: Set currency, store address
- **Products**: Configure dimensions, product reviews
- **Shipping**: Add shipping zones and rates
- **Payments**: Set up Stripe, PayPal, or other payment gateways
- **Emails**: Customize order confirmation emails

### 9. Contact Form Setup

The theme includes a built-in AJAX contact form. Alternatively:

1. Install Contact Form 7 plugin
2. Create a new form with fields: Name, Email, Subject (dropdown), Message
3. Copy the form shortcode
4. WP Admin → Settings → set the CF7 form ID

### 10. Social Media Links

1. **WP Admin → Appearance → Customize → Eleventh Set Options → Contact Information**
2. Add your Instagram, Facebook, Pinterest, Twitter URLs

---

## File Structure

```
theme/eleventhset/
├── style.css                          # Theme definition + all CSS
├── functions.php                      # Theme setup, WooCommerce, AJAX handlers
├── header.php                         # Site header with nav
├── footer.php                         # Site footer with columns
├── front-page.php                     # Homepage template
├── page.php                           # Default page template
├── page-about.php                     # About page template
├── page-contact.php                   # Contact page template
├── index.php                          # Blog/archive fallback
├── inc/
│   └── setup-wizard.php               # Admin setup wizard
├── assets/
│   ├── css/
│   │   └── (additional CSS if needed)
│   ├── js/
│   │   └── main.js                    # All JavaScript
│   └── images/
│       └── (upload placeholder images)
└── woocommerce/
    ├── single-product.php             # Single product wrapper
    ├── content-single-product.php     # Single product detail layout
    ├── archive-product.php            # Shop page
    └── content-product.php           # Product card in loops
```

---

## Design Specifications

### Colors
| Name | Hex | Usage |
|------|-----|-------|
| Black | `#0a0a0a` | Primary text, backgrounds |
| White | `#ffffff` | Backgrounds, text on dark |
| Off White | `#f8f7f5` | Section backgrounds |
| Light Gray | `#e8e6e1` | Borders, image placeholders |
| Accent Gold | `#c9a96e` | Labels, highlights, accents |
| Text Secondary | `#666666` | Body copy |

### Typography
- **Headings**: Cormorant Garamond (Google Fonts) — Light 300, Regular 400
- **Body/UI**: Montserrat (Google Fonts) — Light 300, Regular 400, Medium 500, SemiBold 600, Bold 700

### Breakpoints
- Desktop: 1200px+
- Tablet: 768px–1200px
- Mobile: < 768px
- Small Mobile: < 480px

---

## Troubleshooting

**Theme not showing WooCommerce products?**
→ Ensure WooCommerce is installed and activated before the theme

**Navigation menu not appearing?**
→ Run the Setup Wizard (Appearance → Theme Setup) or manually assign under Appearance → Menus

**Contact form not sending?**
→ Check that your WordPress installation can send emails; consider using WP Mail SMTP plugin

**Product images not cropped correctly?**
→ After uploading images, run: WP Admin → Tools → Regenerate Thumbnails (requires plugin)

**Mobile menu not working?**
→ Ensure JavaScript is enabled; check browser console for errors
