<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FreteService
{
    public function calcularFrete($cepDestino)
    {
        try {
            $token = env('MAILCHIMP_API_KEY');
            $cepOrigem = "61913080"; // Seu CEP de origem

            $url = "https://www.melhorenvio.com.br/api/v2/me/shipment/calculate";

            $payload = [
                "from" => ["postal_code" => $cepOrigem],
                "to" => ["postal_code" => $cepDestino],
                "products" => [
                    [
                        "height" => 5, // altura em cm
                        "width" => 20, // largura em cm
                        "length" => 20, // comprimento em cm
                        "weight" => 0.5 // peso em kg
                    ]
                ]
            ];
            $response = Http::withToken($token)
            ->timeout(30)
            ->withHeaders(['Accept' => 'application/json'])
            ->post($url, $payload);

            if (!$response->successful()) {
                dd($response->body());
                return ["error" => "Erro ao calcular o frete: " . $response->body()];
            }

            $data = $response->json();


            if (empty($data)) {
                return ["error" => "Nenhuma opção de frete encontrada."];
            }

            $opcaoFrete = collect($data)->first(fn($opcao) => isset($opcao["price"]));

            if (!$opcaoFrete) {
                return ["error" => "Nenhum serviço de frete disponível com preço válido."];
            }

            return [
                "valor" => number_format($opcaoFrete["price"], 2, ',', '.'),
                "prazo" => $opcaoFrete["delivery_time"] ?? 'Indisponível'
            ];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
