<?php

namespace Tests\Feature;

use App\Book;
use App\User;
use App\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationsTest extends TestCase
{
    /** @test */

    use RefreshDatabase;
    public function a_book_can_be_checked_out()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(),Reservation::first()->checked_out_at);
    }

    /** @test */

    public function a_book_can_be_returned()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertNotNull(Reservation::first()->checked_in_at);
        $this->assertEquals(now(),Reservation::first()->checked_in_at);

    }


    /** @test */

    public function if_not_checked_out_exception_thrown()
    {
        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkin($user);

    }
    /** @test0 */

    public function a_book_can_be_checked_out_twice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);

        $book->checkin($user);

        $book->checkout($user);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(),Reservation::find(2)->checked_out_at);

        $book->checkin($user);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNotNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(),Reservation::find(2)->checked_in_at);
    }
}