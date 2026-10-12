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

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('email', 'admin@chamundaelectrics.com')->first();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Overview');
        $response->assertSee('Total Sales Revenue');
    }

    public function test_admin_can_access_products_and_orders(): void
    {
        $admin = User::where('email', 'admin@chamundaelectrics.com')->first();
        
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
    }
}
