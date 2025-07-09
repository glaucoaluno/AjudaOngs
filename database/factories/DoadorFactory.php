<?php

namespace Database\Factories;

use App\Models\Doador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doador>
 */
class DoadorFactory extends Factory
{
    /**
     * Nome da model correspondente ao factory.
     *
     * @var string
     */
    protected $model = Doador::class;

    /**
     * Define o estado padrão da model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->regexify('\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}'),
            'endereco' => $this->generateShortAddress()
        ];
    }

    /**
     * Gera um endereço mais curto para evitar exceder limites da tabela.
     *
     * @return string
     */
    private function generateShortAddress(): string
    {
        $street = $this->faker->streetName();
        $number = $this->faker->buildingNumber();
        $city = $this->faker->city();
        
        // Limita o tamanho total do endereço
        $address = "$street, $number - $city";
        
        // Se ainda estiver muito longo, trunca mantendo a estrutura
        if (strlen($address) > 45) { // Deixa margem de segurança
            $shortStreet = substr($street, 0, 15);
            $shortCity = substr($city, 0, 15);
            $address = "$shortStreet, $number - $shortCity";
        }
        
        return $address;
    }

    /**
     * Garante que o doador deve ser de uma cidade específica.
     *
     * @return static
     */
    public function fromCity(string $city, string $state = 'SP'): static
    {
        return $this->state(fn (array $attributes) => [
            'endereco' => $this->generateCityAddress($city, $state)
        ]);
    }

    /**
     * Gera endereço para cidade específica com limite de caracteres.
     *
     * @param string $city
     * @param string $state
     * @return string
     */
    private function generateCityAddress(string $city, string $state): string
    {
        $street = $this->faker->streetName();
        $number = $this->faker->buildingNumber();
        $postcode = $this->faker->postcode();
        
        // Estrutura: Rua, Número, Cidade - Estado, CEP
        $address = "$street, $number, $city - $state, $postcode";
        
        // Se exceder 45 caracteres, simplifica
        if (strlen($address) > 45) {
            $shortStreet = substr($street, 0, 10);
            $address = "$shortStreet, $number, $city - $state";
        }
        
        return $address;
    }

    /**
     * Garante que o doador deve ter um número de telefone móvel.
     *
     * @return static
     */
    public function withMobilePhone(): static
    {
        return $this->state(fn (array $attributes) => [
            'telefone' => $this->faker->regexify('\([0-9]{2}\) 9[0-9]{4}-[0-9]{4}')
        ]);
    }

    /**
     * Garante que o doador foi registrado em uma data específica.
     *
     * @return static
     */
    public function registeredOn(\DateTime $date): static
    {
        return $this->state(fn (array $attributes) => [
            'data_cadastro' => $date
        ]);
    }

    /**
     * Gera dados com padrão brasileiro.
     *
     * @return static
     */
    public function brazilian(): static
    {
        $brazilianNames = [
            'João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Pereira',
            'Fernanda Lima', 'Ricardo Souza', 'Juliana Alves', 'Marcos Ribeiro', 'Camila Ferreira',
            'Paulo Martins', 'Beatriz Rodrigues', 'Lucas Barbosa', 'Gabriela Carvalho', 'Thiago Nascimento'
        ];
        
        $brazilianCities = [
            'São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Salvador', 'Brasília',
            'Curitiba', 'Recife', 'Porto Alegre', 'Manaus', 'Campinas'
        ];
        
        $states = ['SP', 'RJ', 'MG', 'BA', 'DF', 'PR', 'PE', 'RS', 'AM', 'GO'];

        return $this->state(fn (array $attributes) => [
            'nome' => $this->faker->randomElement($brazilianNames),
            'endereco' => $this->generateBrazilianAddress($brazilianCities, $states)
        ]);
    }

    /**
     * Gera endereço brasileiro com limite de caracteres.
     *
     * @param array $cities
     * @param array $states
     * @return string
     */
    private function generateBrazilianAddress(array $cities, array $states): string
    {
        $street = $this->faker->streetName();
        $number = $this->faker->buildingNumber();
        $city = $this->faker->randomElement($cities);
        $state = $this->faker->randomElement($states);
        $postcode = $this->faker->regexify('[0-9]{5}-[0-9]{3}');
        
        // Tenta formato completo primeiro
        $fullAddress = "$street, $number, $city - $state, $postcode";
        
        // Se exceder 45 caracteres, simplifica progressivamente
        if (strlen($fullAddress) > 45) {
            $shortAddress = "$street, $number, $city - $state";
            if (strlen($shortAddress) > 45) {
                $shortStreet = substr($street, 0, 10);
                $shortAddress = "$shortStreet, $number, $city - $state";
                if (strlen($shortAddress) > 45) {
                    $shortCity = substr($city, 0, 10);
                    $shortAddress = "$shortStreet, $number, $shortCity - $state";
                }
            }
            return $shortAddress;
        }
        
        return $fullAddress;
    }
}