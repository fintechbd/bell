<?php

namespace Fintech\Bell\Seeders;

use Fintech\Bell\Facades\Bell;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data() as $entry) {
            Bell::template()->create($entry);
        }
    }

    private function data()
    {
        return [
            [

            ],
        ];
    }
}
