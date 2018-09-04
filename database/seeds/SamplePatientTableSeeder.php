<?php
 
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
 
class SamplePatientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=SamplePatientTableSeeder
     * @return void
     */
    public function run()
    {
      $faker = Faker::create();
      $faker->addProvider(new \Faker\Provider\vi_VN\Person($faker));
      $faker->addProvider(new \Faker\Provider\vi_VN\Address($faker));
      $faker->addProvider(new \Faker\Provider\vi_VN\PhoneNumber($faker));
      foreach (range(1,100) as $index) {
        DB::table('sample_patients')->insert([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName . ' ' . $faker->middleName,
            'id_card_no' => $faker->ein,
            'phone_no' => $faker->phoneNumber,
            'email' => $faker->email,
            'sex' => $faker->randomElements($array = array ('F','M','O'), $count = 1)[0],
            'birth_date' =>$faker->date($format = 'Y-m-d'),
            'height' =>  $faker->numberBetween($min = 149, $max = 185),
            'weight' => $faker->numberBetween($min = 45, $max = 105),
            'address' => $faker->address,
            'created_at' => $faker->dateTime($max = 'now'),
            'updated_at' => $faker->dateTime($max = 'now'),
        ]);
      }
    }
}