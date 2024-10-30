<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'A' => 'Active',
            'C' => 'Cancelled',
            'R' => 'Renewed',
            'P' => 'Pending',
            'S' => 'Suspended',
            'H' => 'On Hold'
        ];

        foreach ($statuses as $status => $label) {
            Status::create([
                'code' => $status,
                'name' => $label
            ]);
        }
    }
}
