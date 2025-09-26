# product-carousel-for-woocommerce-with-arrows-code-snippet

# WooCommerce Category Slider

This snippet adds a **WooCommerce Category/Product Slider** powered by [Swiper.js](https://swiperjs.com/).
It provides a shortcode you can use anywhere on your WordPress site to display a product carousel.

---

## 📌 Features

* Swiper.js powered responsive slider.
* Supports autoplay, pagination, and navigation arrows.
* Adjustable columns for **mobile**, **tablet**, and **desktop**.
* Customizable product limit and specific category filter.
* Clean, modern styling with responsive design.

---

## 🔧 Installation

1. Copy the provided PHP code.
2. Paste it into your theme’s `functions.php` file or a custom plugin.
3. Make sure **WooCommerce** is installed and active.

---

## 🖇️ Shortcode

Use the shortcode:

```php
[wc_category_slider]
```

---

## ⚙️ Shortcode Attributes

You can customize the slider using attributes:

| Attribute      | Default | Description                                                |
| -------------- | ------- | ---------------------------------------------------------- |
| `category`     | `""`    | Category **slug** or **ID**. Leave empty for all products. |
| `limit`        | `8`     | Number of products to display.                             |
| `autoplay`     | `true`  | Enable/disable autoplay (`true` / `false`).                |
| `speed`        | `4000`  | Autoplay delay in milliseconds.                            |
| `mobile_cols`  | `1.5`   | Number of slides visible on **mobile**.                    |
| `tablet_cols`  | `2.5`   | Number of slides visible on **tablet**.                    |
| `desktop_cols` | `4`     | Number of slides visible on **desktop**.                   |

---

## 🧩 Examples

**1. Basic usage (all products, default settings):**

```php
[wc_category_slider]
```

**2. Show 6 products from the category "shoes":**

```php
[wc_category_slider category="shoes" limit="6"]
```

**3. Disable autoplay, show 10 products with custom columns:**

```php
[wc_category_slider limit="10" autoplay="false" mobile_cols="2" tablet_cols="3" desktop_cols="5"]
```

---

## 🎨 Styling

* The slider comes with default **modern styling** for product cards, titles, prices, pagination, and navigation arrows.
* Arrows are hidden on **mobile** for better UX.
* You can override styles in your theme’s CSS if needed.

---

## ✅ Requirements

* WordPress
* WooCommerce
* PHP 7.4+
* Active internet connection (loads Swiper assets from CDN)

