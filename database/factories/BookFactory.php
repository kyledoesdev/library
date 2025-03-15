<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_id' => Author::factory(),
            'category_id' => Category::inRandomOrder()->first()->getKey(),
            'title' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(3),
            'cover' => 'https://api.dicebear.com/9.x/icons/svg?icon=book',
            'publisher' => $this->faker->company(),
            'published_at' => $this->faker->dateTimeBetween('-30 years', 'now'),
            'isbn' => $this->faker->isbn13(),
            'page_count' => $this->faker->numberBetween(50, 1200),
            'quantity' => 1,
        ];
    }

    public function checkout()
    {
        return $this->afterCreating(function (Book $book) {
            $user = User::inRandomOrder()->first() ?? User::factory()->create();
            
            Checkout::create([
                'book_id' => $book->getKey(),
                'user_id' => $user->getKey(),
                'checked_out_at' => now(),
                'due_at' => now()->addDays(5),
            ]);

            $book->update([
                'quantity' => 0
            ]);
        });
    }
}
