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
        $bannersRes->assertSee('Storefront Hero Banners');
    }

    public function test_admin_can_manage_hero_banners(): void
    {
        $admin = User::where('email', 'mayurinakum@gmail.com')->first();

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
}

