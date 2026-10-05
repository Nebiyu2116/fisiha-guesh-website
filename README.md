# Fisiha Guesh Import & Export Website

## Pages
- index.html
- about.html
- products.html
- coffee.html
- vegetables.html
- oil-seeds.html
- contact.html

## Product navigation
Home now links each export product to its own page:
- Coffee -> coffee.html
- Vegetables -> vegetables.html
- Oil Seeds -> oil-seeds.html

The header's Contact/WhatsApp button directly opens WhatsApp:
+251-911-34-91-79

## Company
Established: 2019 G.C.
Address: Addis Ababa, Ethiopia, Arada Sub City, Wereda 01, Atiklit Tera Building G010
Phone: +251-911-34-91-79
WhatsApp: +251-911-34-91-79 / +251-934-09-50-31
Email: fisihasamuel12@gmail.com
Exports: Coffee, Vegetables, Oil Seeds

## Product images
The vegetable and oil-seed pages contain dedicated visual product areas. They are currently styled placeholders so real company/product photographs can be inserted later without changing the layout.


## Images
Product images are loaded from Wikimedia Commons using Creative Commons-licensed sources. The image credits and licenses are listed in `IMAGE-CREDITS.md`.

## Contact form
The contact form now POSTs to `contact.php`, validates input server-side, rejects header-injection characters, uses a honeypot and a basic session rate limit, and sends the message to `fisihasamuel12@gmail.com`. The server must support PHP `mail()` (or its configured mail transport). The visitor's email is placed in `Reply-To`, not in the `From` header.

## Security
A `.htaccess` file adds security headers, disables directory listing, restricts access to common sensitive files, and applies a Content Security Policy. For production, enable HTTPS on the hosting account and configure the host's mail system/SPF/DKIM/DMARC.
