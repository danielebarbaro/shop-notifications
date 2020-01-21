<?php

namespace Tests\Feature;

use App\Helpers\Constant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProductTest extends TestCase
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
        $this->product = factory(Product::class)->create();
    }

    /**
     * Create a new product
     *
     * @return void
     */
    public function testUpdateProduct()
    {
        $this
            ->actingAs($this->user, 'web')
            ->put("products/{$this->product->id}", [
                'name' => 'Margherita',
                'price' => '5.1',
                'type' => Constant::PRODUCT_TYPE_0
            ]);
        $product_edited = Product::latest()->first();
        $this->assertAuthenticatedAs($this->user);
        $this->assertEquals('Margherita', $product_edited->name);
        $this->assertEquals('5.1', $product_edited->price);
        $this->assertEquals(Constant::PRODUCT_TYPE_0, $product_edited->type);
    }

    /**
     * Verify an empty product
     *
     * @return void
     */
    public function testPostNewProductRequestNoBody()
    {
        $response = $this
            ->put("products/{$this->product->id}", [
                'price' => '6.16',
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
            ->put("products/{$this->product->id}", [
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
            ->put("products/{$this->product->id}", [
                'price' => '4.3',
                'type' => '',
                'name' => 'Capricciosa'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['type']);
    }
}
