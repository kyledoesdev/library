<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class RefreshBookList extends Command
{
    protected $signature = 'kyles-library:refresh-books';

    protected $description = 'Add 10 new books.';

    public function handle()
    {
        Book::factory(10)->create(); /* nice, right? Lol */
    }
}
