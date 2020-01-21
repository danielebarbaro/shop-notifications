<?php

namespace Tests\Feature;

use App\Helpers\Constant;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProductUpdateNotificationTest extends TestCase
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
     * Test a notification
     *
     * @return void
     */
    public function testUpdateNotificationProduct()
    {
        Notification::fake();
        $this
            ->actingAs($this->user, 'web')
            ->put("products/{$this->product->id}", [
                'name' => 'Margherita',
                'price' => '5.1',
                'type' => Constant::PRODUCT_TYPE_0
            ]);
        Notification::assertSentTo($this->user, ProductUpdatedNotification::class);
    }
}
