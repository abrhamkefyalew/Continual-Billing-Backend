<?php

namespace Database\Seeders;

use App\Models\Directive;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DirectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        foreach (Directive::allowedTypes() as $directiveType) {

            Directive::updateOrCreate(
                
                ['directive_type' => $directiveType], //   //  //  //  //  //  // Ensure uniqueness

                [
                    'is_active' => true,
                    'name' => null,
                ]
            );

        }
        
    }
}
