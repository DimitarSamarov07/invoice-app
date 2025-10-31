<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "number" => fake()->unique()->numerify('INVOICE-##########'),
            "customer_name" => fake()->company(),
            "customer_email" => fake()->email(),
            "date" => fake()->dateTimeBetween('-1 month', 'now'),
            "due_date" => fake()->dateTimeBetween('now', '+1 month'),
            "status" => fake()->randomElement(['unpaid', 'paid', 'draft']),
            "subtotal" => 0,
            "vat" => 0
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($invoice) {
            $items = InvoiceItem::factory(rand(3, 5))->create([
                'invoice_id' => $invoice->id,
            ]);

            $subtotal = $items->sum('total');
            $vat = round($subtotal * 0.2, 2);

            $invoice->subtotal = $subtotal;
            $invoice->vat = $vat;

            $invoice->save();
        });
    }
}
