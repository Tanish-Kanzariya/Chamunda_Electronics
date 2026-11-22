<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Tests\TestCase;

class ChamundaElectricsTest extends TestCase
{
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Chamunda Electrics');
    }

    public function test_shop_catalog_loads_with_products(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Polycab');
    }

    public function test_product_detail_page_loads(): void
    {
        $product = Product::first();
        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_cart_operations(): void
    {
        $product = Product::first();
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $response->assertSessionHas('cart');

        $cartResponse = $this->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($product->name);
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $response = $this->actingAs($customer)->get('/admin');
        $response->assertRedirect('/');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('email', 'mayurinakum@gmail.com')->first();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Overview');
        $response->assertSee('Total Sales Revenue');
    }

    public function test_admin_can_access_products_and_orders(): void
    {
        $admin = User::where('email', 'mayurinakum@gmail.com')->first();
        
        $productsRes = $this->actingAs($admin)->get('/admin/products');
        $productsRes->assertStatus(200);

        $ordersRes = $this->actingAs($admin)->get('/admin/orders');
        $ordersRes->assertStatus(200);

        $categoriesRes = $this->actingAs($admin)->get('/admin/categories');
        $categoriesRes->assertStatus(200);

        $brandsRes = $this->actingAs($admin)->get('/admin/brands');
        $brandsRes->assertStatus(200);

        $couponsRes = $this->actingAs($admin)->get('/admin/coupons');
        $couponsRes->assertStatus(200);

        $bannersRes = $this->actingAs($admin)->get('/admin/banners');
        $bannersRes->assertStatus(200);
        $bannersRes->assertSee('Storefront Hero');
    }

    public function test_admin_can_manage_hero_banners(): void
    {
        $admin = User::where('email', 'mayurinakum@gmail.com')->first();
        \App\Models\Banner::where('title', 'Special Contractor Discount Weekend')->delete();

        // 1. Create a banner
        $response = $this->actingAs($admin)->post('/admin/banners', [
            'title' => 'Special Contractor Discount Weekend',
            'badge_text' => 'WEEKEND DEAL',
            'subtitle' => 'Special flat discount on commercial conduit pipes and heavy cables.',
            'image_url_input' => 'images/banners/banner-mega-sale.svg',
            'link_url' => '/shop',
            'button_text' => 'Shop Discount',
            'sort_order' => 5,
            'is_active' => '1',
        ]);
        $response->assertRedirect('/admin/banners');

        $banner = \App\Models\Banner::where('title', 'Special Contractor Discount Weekend')->first();
        $this->assertNotNull($banner);
        $this->assertTrue($banner->is_active);

        // 2. Toggle active status
        $toggleRes = $this->actingAs($admin)->post('/admin/banners/' . $banner->id . '/toggle');
        $toggleRes->assertSessionHas('success');
        $this->assertFalse($banner->fresh()->is_active);
    }

    public function test_homepage_renders_active_hero_banners(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('heroBannerCarousel');
        $response->assertSee('banner-mega-sale.svg');
    }

    public function test_product_detail_has_buy_now_button_and_wishlist_heart(): void
    {
        $product = Product::first();
        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee('Buy Now');
        $response->assertSee('btn-wishlist-toggle');
    }

    public function test_wishlist_toggle_ajax_fills_heart(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $product = Product::first();

        // 1. Add to wishlist
        $response = $this->actingAs($customer)->postJson('/wishlist/toggle', [
            'product_id' => $product->id,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_wishlisted' => true,
            'action' => 'added',
        ]);

        // 2. Remove from wishlist
        $response2 = $this->actingAs($customer)->postJson('/wishlist/toggle', [
            'product_id' => $product->id,
        ]);
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_wishlisted' => false,
            'action' => 'removed',
        ]);
    }

    public function test_buy_now_direct_checkout_particular_product(): void
    {
        $product = Product::first();
        $customer = User::where('email', 'customer@example.com')->first();

        // Put a dummy item in regular cart first to ensure Buy Now doesn't wipe it
        $this->withSession(['cart' => [
            999 => [
                'id' => 999,
                'name' => 'Saved In Cart Item',
                'price' => 100,
                'quantity' => 1,
                'image' => null,
            ]
        ]]);

        // Instant Buy Now
        $buyNowRes = $this->actingAs($customer)->post('/buy-now', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $buyNowRes->assertRedirect(route('checkout.index', ['direct' => 1]));

        // View checkout in Buy Now mode
        $checkoutView = $this->actingAs($customer)->get('/checkout');
        $checkoutView->assertStatus(200);
        $checkoutView->assertSee('Instant 1-Click Buy Now Mode');
        $checkoutView->assertSee($product->name);

        // Submit order with real-world details
        $processRes = $this->actingAs($customer)->post('/checkout', [
            'customer_name' => 'Rahul Patel',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '9876543210',
            'shipping_address' => 'Shop 4, Shree Complex, Station Road',
            'landmark' => 'Opposite SBI Bank',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380001',
            'address_type' => 'commercial_site',
            'payment_method' => 'cod',
            'is_buy_now' => 1,
        ]);
        $processRes->assertRedirect();
        
        // Assert regular cart was preserved!
        $this->assertTrue(session()->has('cart'));
    }
}


