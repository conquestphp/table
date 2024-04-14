<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;
use Workbench\App\Models\TestUser;
use Workbench\App\Tables\BasicTable;

class BasicTableTest extends Testcase
{
    public function test_load_table()
    {
        /** All tests are performed as endpoints, not making */
        $content = $this->get('/basic')->assertStatus(200)->getContent();
        $this->assertJson($content);
        $table = json_decode($content)->table;
        $this->assertEquals($table->meta->per_page, 10);
        $this->assertEquals($table->recordKey, 'id');
        $this->assertEquals(count($table->actions->inline), 3);
        $this->assertEquals(count($table->actions->page), 1);
        $this->assertEquals(count($table->actions->bulk), 1);
        $this->assertEquals(count(get_object_vars($table->refinements->sorts)), 2);
        $this->assertEquals(count(get_object_vars($table->refinements->filters)), 2);

        $this->assertEquals($table->refinements->sorts->newest->active, true);
        $this->assertEquals($table->refinements->sorts->oldest->active, false);
        
        foreach ($table->refinements->filters as $key => $value) {
            $this->assertEquals($value->active, false);
        }
    }

    // public function test
}