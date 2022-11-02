<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    function test_can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ]);
    }

    /**
     * @test
     */
    function can_get_one_book()
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /**
     * @test
     */
    function can_create_one_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor(('title'));

        $this->postJson(route('books.store'), [
            'title' => 'las mil y una noche'
        ])->assertJsonFragment([
            'title' => 'las mil y una noche'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'las mil y una noche'
        ]);
    }

    /**
     * @test
     */
    function can_update_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor(('title'));

        $this->patchJson(route('books.update', $book), [
            'title' => 'las mil y una noche'
        ])->assertJsonFragment([
            'title' => 'las mil y una noche'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'las mil y una noche'
        ]);
    }

    /**
     * @test
     */
    function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
