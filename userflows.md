Below are the **end-to-end user flows** optimized for guest-first shopping with optional account creation.  
Designed around our database schema with session-based carts, inventory management, and manual payment workflows.

------------------------------------------------
FLOW A – Guest Discovery → Manual Payment
------------------------------------------------

1. Landing  
   - Guest arrives on homepage
   - Sees hero banner, search bar, category cards (Phones, Laptops, Audio...)
   - Cart persists via session token (no login required)

2. Browse by category  
   - Clicks "Phones" card
   - Product list page with nested categories (iPhone > iPhone 15 > iPhone 15 Pro)
   - Side filters: price range, color swatches, storage options
   - All filtering via Livewire (no page reloads)

3. Product detail  
   - Clicks product card → product detail page
   - Image gallery shows primary product images
   - Price shows "From €1,199" (cheapest variant)
   - Variant selector shows available axes (Color, Storage, etc.)
   - Stock status per variant from inventory table

4. Variant selection  
   - Color swatches update images and price in real-time
   - Storage pills show price deltas (+€200 for 512GB)
   - "Add to Cart" only enabled when variant selected
   - Stock reservation happens on add (expires in 15 minutes)

5. Cart management  
   - Session-based cart (no account needed)
   - Shows variant images, chosen attributes, quantities
   - Real-time stock validation
   - Coupon code input with instant validation
   - "Continue as Guest" or "Sign In" options

6. Guest checkout  
   - Email + phone required for order updates
   - Shipping address form
   - Delivery options based on location/postal code:
     * Courier delivery (calculated by delivery zones)
     * Pickup locations (filtered by distance)
     * Bus delivery to stations
   - Delivery price calculated from zones table

7. Payment method  
   - Manual payment options:
     * Bank transfer (IBAN details)
     * Mobile money (M-Pesa, etc.)
     * Crypto wallet addresses
   - "Pay now and enter reference" workflow
   - Clear instructions with seller contact info

8. Order completion  
   - Guest enters payment reference
   - Order created with status 'pending'
   - Stock reserved → confirmed
   - Automated notifications:
     * WhatsApp to seller with order details + product images
     * Email to buyer with order summary
     * Email to seller with payment confirmation link

9. Order tracking (guest)  
   - Order lookup via email + order number (no account needed)
   - Status progression: pending → paid → shipped → delivered
   - WhatsApp updates at each stage
   - Optional: "Create account to save this order" prompt

10. Seller workflow  
    - Filament admin receives order notifications
    - Payment verification screen with transaction details
    - One-click status updates trigger customer notifications
    - Inventory automatically adjusted
    - Shipping label generation integration

------------------------------------------------
FLOW B – Search-First Discovery
------------------------------------------------

1. Instant search  
   - Guest types "iphone 15" in search bar
   - MeiliSearch returns instant results:
     * Product suggestions with images
     * Category matches
     * "See all X results" link
   - Search tracks popular queries for analytics

2. Search results  
   - Full-text search across product names, descriptions, variant attributes
   - Smart filters auto-generated from search results
   - Sort by: relevance, price, newest, ratings
   - Faceted search with variant attributes

3. Product selection  
   - Same product detail flow as Flow A
   - Search terms highlighted in product descriptions
   - "Customers also searched for" suggestions

4. Checkout continues as Flow A

------------------------------------------------
FLOW C – Optional Account Creation
------------------------------------------------

1. Account creation triggers:
   - After successful order: "Save your details for faster checkout"
   - During checkout: "Create account to track orders"
   - When adding to wishlist: "Sign up to save favorites"
   - Never forced, always optional

2. Account benefits:
   - Order history and tracking
   - Saved addresses and payment preferences
   - Wishlist persistence across devices
   - Faster checkout (pre-filled forms)
   - Review and rating capabilities

------------------------------------------------
Edge Cases & Error Handling
------------------------------------------------

- **Stock conflicts**: Real-time validation, alternative suggestions
- **Payment timeout**: 24h grace period, then auto-cancel with notifications
- **Invalid delivery zones**: Fallback to pickup options
- **Coupon edge cases**: Usage limits, expiry, minimum amounts
- **Guest order lookup**: Email + order number, no account required
- **Abandoned carts**: Stock auto-released after 15 minutes
- **Seller offline**: Clear messaging about response times
- **Return requests**: Simple form, photo upload, status tracking

------------------------------------------------
Key Design Principles
------------------------------------------------

- **Guest-first**: Full shopping experience without account
- **Progressive enhancement**: Account creation adds value, never required
- **Mobile-optimized**: Touch-friendly, fast loading
- **Real-time feedback**: Stock, pricing, delivery options
- **Clear communication**: Order status, payment instructions, delivery updates
- **Seller efficiency**: Streamlined admin, automated notifications

These flows leverage our database schema for inventory management, variant selection, delivery zones, and guest order tracking while keeping the experience friction-free.