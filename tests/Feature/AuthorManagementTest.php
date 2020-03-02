<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    /** @test */
    use RefreshDatabase;

    public function an_author_can_be_created()
    {
        $this->withoutExceptionHandling();

        $this->post('/author',[
            'name' => 'Author Name',
            'dob' => '05/14/2001',
        ]);

        $author = Author::all();
        $this->assertCount(1,$author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('2001/05/14',$author->first()->dob->format('Y/m/d'));
    }
}
