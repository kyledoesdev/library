<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createCategories();

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Librarian',
            'timezone' => 'America/New_York',
            'avatar' => "https://api.dicebear.com/7.x/initials/svg?seed=Test Librarian",
            'email' => 'librarian@mail.com',
            'is_librarian' => true
        ]);

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'timezone' => 'America/New_York',
            'avatar' => "https://api.dicebear.com/7.x/initials/svg?seed=Test Customer",
            'email' => 'customer@mail.com',
            'is_librarian' => false
        ]);

        User::factory(50)->create();

        Book::factory(10)->withActiveCheckout()->withReview()->create();
        Book::factory(10)->withReview()->create();
        Book::factory(15)->isFeatured()->create();
        Book::factory(15)->create();
    }

    private function createCategories(): void
    {
        Category::create(['name' => 'Fiction']);
        Category::create(['name' => 'Non-Fiction']);
        Category::create(['name' => 'Mystery']);
        Category::create(['name' => 'Science Fiction']);
        Category::create(['name' => 'Fantasy']);
        Category::create(['name' => 'Romance']);
        Category::create(['name' => 'Thriller']);
        Category::create(['name' => 'Horror']);
        Category::create(['name' => 'Biography']);
        Category::create(['name' => 'Autobiography']);
        Category::create(['name' => 'History']);
        Category::create(['name' => 'Self-Help']);
        Category::create(['name' => 'Business']);
        Category::create(['name' => 'Technology']);
        Category::create(['name' => 'Science']);
        Category::create(['name' => 'Philosophy']);
        Category::create(['name' => 'Poetry']);
        Category::create(['name' => 'Drama']);
        Category::create(['name' => 'Young Adult']);
        Category::create(['name' => 'Children\'s Literature']);
        Category::create(['name' => 'Travel']);
        Category::create(['name' => 'Cooking']);
        Category::create(['name' => 'Art']);
        Category::create(['name' => 'Music']);
        Category::create(['name' => 'Sports']);
        Category::create(['name' => 'True Crime']);
        Category::create(['name' => 'Graphic Novel']);
        Category::create(['name' => 'Comic Book']);
        Category::create(['name' => 'Dystopian']);
        Category::create(['name' => 'Classic Literature']);
        Category::create(['name' => 'Memoir']);
        Category::create(['name' => 'Essays']);
        Category::create(['name' => 'Short Stories']);
        Category::create(['name' => 'Adventure']);
        Category::create(['name' => 'Historical Fiction']);
        Category::create(['name' => 'Satire']);
    }
}
