# Product Variant System

## Overview
The variant system allows products to have multiple variations (Size, Color, Material, etc.) with individual pricing, inventory, and images.

## How It Works

### 1. Variant Axes
Define what makes variants different:
- **Size**: S, M, L, XL
- **Color**: Red, Blue, Green, Black
- **Material**: Cotton, Polyester, Blend

### 2. Variant Generation
The system creates all possible combinations using Cartesian product:
- Size (3) × Color (4) = 12 variants
- Each combination gets: SKU, price, inventory, images

### 3. Admin Workflow
1. Create product with basic info
2. Upload general product images
3. Define variant axes and values
4. Click "Generate Variants" button
5. Configure each variant:
   - Set unique SKU
   - Set price in cents
   - Upload variant-specific images
   - Toggle active/inactive

### 4. Image Strategy
- **Product Images**: General shots, lifestyle, size charts
- **Variant Images**: Color-specific photos, material close-ups
- **Frontend**: Shows variant images when selected, falls back to product images

## Database Structure

### Products Table
- Basic product info (name, description, category)
- General images via MediaLibrary

### Variants Table
- Links to product
- SKU, price, cost, dimensions
- Individual images via MediaLibrary
- Active status

### Variant Axes & Values
- Flexible axis definition (Size, Color, etc.)
- Values per axis (S/M/L, Red/Blue)
- Links variants to their axis combinations

## Example Usage

**T-Shirt Product:**
1. Axes: Size (S,M,L), Color (Red,Blue)
2. Generated: 6 variants
3. SKUs: TSHIRT-S-RED, TSHIRT-M-RED, etc.
4. Pricing: Small=1999¢, Large=2499¢
5. Images: Red variants get red photos, blue get blue photos

**Book Product:**
1. No variant axes
2. Single "default" variant
3. One SKU, one price
4. Product images only