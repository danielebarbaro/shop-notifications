<?php

namespace Tests\Feature;

use App\Helpers\Constant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostNewProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
//        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->user = factory(User::class)->create();
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function testPostNewProduct()
    {
        $this
            ->actingAs($this->user, 'web')
            ->post('products', [
                'name' => 'Margherita',
                'price' => '5.1',
                'type' => Constant::PRODUCT_TYPE_0
            ]);
        $product = Product::latest()->first();
        $this->assertAuthenticatedAs($this->user);
        $this->assertEquals('Margherita', $product->name);
        $this->assertEquals('5.1', $product->price);
        $this->assertEquals(Constant::PRODUCT_TYPE_0, $product->type);
    }

    /**
     * Verify an empty product
     *
     * @return void
     */
    public function testPostNewProductRequestNoBody()
    {
        $response = $this
            ->post('products', [
                'price' => '6.6',
                'name' => '',
                'type' => Constant::PRODUCT_TYPE_1
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Verify an empty price
     *
     * @return void
     */
    public function testPostNewProductRequestNoPrice()
    {
        $response = $this
            ->post('products', [
                'price' => '',
                'type' => Constant::PRODUCT_TYPE_1,
                'name' => 'Capricciosa'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * Verify an empty price
     *
     * @return void
     */
    public function testPostNewProductRequestNoType()
    {
        $response = $this
            ->post('products', [
                'price' => '4.3',
                'type' => '',
                'name' => 'Capricciosa'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['type']);
    }
}
