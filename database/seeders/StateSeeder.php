<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('states')->upsert(
            [
                ["name" => "Santa Catarina", "ibge_code" => 42, "uf" => "SC"],
                ["name" => "Rondônia", "ibge_code" => 11, "uf" => "RO"],
                ["name" => "Acre", "ibge_code" => 12, "uf" => "AC"],
                ["name" => "Amazonas", "ibge_code" => 13, "uf" => "AM"],
                ["name" => "Roraima", "ibge_code" => 14, "uf" => "RR"],
                ["name" => "Pará", "ibge_code" => 15, "uf" => "PA"],
                ["name" => "Amapá", "ibge_code" => 16, "uf" => "AP"],
                ["name" => "Tocantins", "ibge_code" => 17, "uf" => "TO"],
                ["name" => "Maranhão", "ibge_code" => 21, "uf" => "MA"],
                ["name" => "Piauí", "ibge_code" => 22, "uf" => "PI"],
                ["name" => "Ceará", "ibge_code" => 23, "uf" => "CE"],
                ["name" => "Rio Grande do Norte", "ibge_code" => 24, "uf" => "RN"],
                ["name" => "Paraíba", "ibge_code" => 25, "uf" => "PB"],
                ["name" => "Pernambuco", "ibge_code" => 26, "uf" => "PE"],
                ["name" => "Alagoas", "ibge_code" => 27, "uf" => "AL"],
                ["name" => "Sergipe", "ibge_code" => 28, "uf" => "SE"],
                ["name" => "Bahia", "ibge_code" => 29, "uf" => "BA"],
                ["name" => "Minas Gerais", "ibge_code" => 31, "uf" => "MG"],
                ["name" => "Espirito Santo", "ibge_code" => 32, "uf" => "ES"],
                ["name" => "Rio de Janeiro", "ibge_code" => 33, "uf" => "RJ"],
                ["name" => "São Paulo", "ibge_code" => 35, "uf" => "SP"],
                ["name" => "Paraná", "ibge_code" => 41, "uf" => "PR"],
                ["name" => "Rio Grande do Sul", "ibge_code" => 43, "uf" => "RS"],
                ["name" => "Mato Grosso do Sul", "ibge_code" => 50, "uf" => "MS"],
                ["name" => "Mato Grosso", "ibge_code" => 51, "uf" => "MT"],
                ["name" => "Goiás", "ibge_code" => 52, "uf" => "GO"],
                ["name" => "Distrito Federal", "ibge_code" => 53, "uf" => "DF"],
            ],
            ['ibge_code'], 
            ['name', 'uf']

        );
    }
}
