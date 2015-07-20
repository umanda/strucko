<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// User
$factory->define(App\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
    ];
});

// Synonym
$factory->define(App\Synonym::class, function($faker) {
    return [
        
    ];
});

// Language
$factory->define(App\Language::class, function($faker) {
    return [
        'id' => $faker->unique()->languageCode,
        'part2b' => 'p2b',
        'part2t' => 'p2t',
        'part1' => 'p1',
        'scope' => $faker->randomElement(['I', 'M', 'S']),
        'type' => $faker->randomElement(['A', 'C', 'E', 'H', 'L', 'S']),
        'ref_name' => $faker->word,
        'comment' => $faker->optional()->sentence,
        'active' => $faker->randomElement([0,1]),
    ];
});

// TermStatus
$factory->define(App\TermStatus::class, function($faker) {
    return [
        'term_status' => $faker->unique()->numerify('Status #'),
        'rank' => $faker->unique()->randomNumber($nbDigits = 1),
    ];
});

// PartOfSpeech
$factory->define(App\PartOfSpeech::class, function($faker) {
    return [
        'part_of_speech' => $faker->word,
    ];
});

// ScientificArea
$factory->define(App\ScientificArea::class, function($faker) {
    return [
        'scientific_area' => $faker->word,
    ];
});

// ScientificField
$factory->define(App\ScientificField::class, function($faker) {
    return [
        'scientific_field' => $faker->word,
        'scientific_area_id' =>$faker
            ->randomElement(App\ScientificArea::all()
            ->lists('id')
            ->toArray()),
    ];
});

// ScientificBranch
$factory->define(App\ScientificBranch::class, function($faker) {
    return [
        'scientific_branch' => $faker->word,
        'scientific_field_id' =>$faker
            ->randomElement(App\ScientificField::all()
            ->lists('id')
            ->toArray()),
    ];
});

// DefinitionStatus
$factory->define(App\DefinitionStatus::class, function($faker) {
    return [
        'definition_status' => $faker->unique()->numerify('Status #'),
        'rank' => $faker->unique()->randomNumber($nbDigits = 1),
    ];
});

// Definition
$factory->define(App\Definition::class, function($faker) {
    return [
        'definition' => $faker->paragraph($nbSentences = 3),
        'synonym_id' => $faker
            ->randomElement(App\Synonym::all()
            ->lists('id')
            ->toArray()),
        'user_id' => $faker
            ->randomElement(App\User::all()
            ->lists('id')
            ->toArray()),
        'definition_status_id' => $faker
            ->randomElement(App\DefinitionStatus::all()
            ->lists('id')
            ->toArray()),
    ];
});

// Model
$factory->define(App\Term::class, function($faker) {
    return [
        'term' => $faker->word,
        'abbreviation' => $faker->optional()->regexify('[A-Z]{2,4}'),
        'slug' => $faker->slug,
        'slug_unique' => $faker->slug,
        'synonym_id' => $faker
            ->randomElement(App\Synonym::all()
            ->lists('id')
            ->toArray()),
        'user_id' => $faker
            ->randomElement(App\User::all()
            ->lists('id')
            ->toArray()),
        'language_id' => $faker
            ->randomElement(App\Language::all()
            ->lists('id')
            ->toArray()),
        'term_status_id' => $faker
            ->randomElement(App\TermStatus::all()
            ->lists('id')
            ->toArray()),
        'part_of_speech_id' => $faker
            ->randomElement(App\PartOfSpeech::all()
            ->lists('id')
            ->toArray()),
        'scientific_branch_id' => $faker
            ->randomElement(App\ScientificBranch::all()
            ->lists('id')
            ->toArray()),
        
    ];
});