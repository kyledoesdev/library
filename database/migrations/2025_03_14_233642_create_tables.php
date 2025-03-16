<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamps();
        });

        Schema::create('categories', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Author::class)->constrained();
            $table->foreignIdFor(Category::class)->constrained();
            $table->string('title');
            $table->text('description');
            $table->string('cover');
            $table->string('publisher');
            $table->datetime('published_at');
            $table->string('isbn');
            $table->integer('page_count');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Book::class)->constrained();
            $table->datetime('checked_out_at');
            $table->datetime('due_at');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Book::class)->constrained();
            $table->integer('stars');
            $table->text('body');
            $table->timestamps();
        });
    }
};
