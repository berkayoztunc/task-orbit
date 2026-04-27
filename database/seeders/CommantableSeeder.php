<?php

namespace Database\Seeders;

use App\Models\Command;
use App\Models\Commantable;
use App\Models\Task;
use App\Models\Lesson;
use App\Models\InternRegister;
use Illuminate\Database\Seeder;

class CommantableSeeder extends Seeder
{
    public function run(): void
    {
        $commands = Command::all();
        
        foreach ($commands as $command) {
            $models = [
                Task::class,
                Lesson::class,
                InternRegister::class
            ];
            
            $targetModel = $models[array_rand($models)];
            $targetInstance = $targetModel::inRandomOrder()->first();

            if ($targetInstance) {
                Commantable::create([
                    'command_id' => $command->id,
                    'commantable_id' => $targetInstance->id,
                    'commantable_type' => $targetModel,
                ]);
            }
        }
    }
}