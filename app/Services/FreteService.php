<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FreteService
{
    protected $cepOrigem = '61913080'; // Seu CEP de origem (Maracanaú)

    public function calcularFrete($cepDestino)
    {
        try {
            // Verifica cidade usando a API ViaCEP
            $cidadeOrigem = $this->getCidadePorCep($this->cepOrigem);
            $cidadeDestino = $this->getCidadePorCep($cepDestino);

            // Se a cidade for a mesma (frete grátis)
            if (strtolower($cidadeOrigem) === strtolower($cidadeDestino)) {
                return [
                    'valor' => 0.00,
                    'prazo' => 1,
                    'grátis' => true
                ];
            }

            // Caso contrário, calcular via Melhor Envio
            $token = config('services.melhor_envio.api_key');
            $url = "https://www.melhorenvio.com.br/api/v2/me/shipment/calculate";

            $payload = [
                "from" => ["postal_code" => $this->cepOrigem],
                "to" => ["postal_code" => $cepDestino],
                "products" => [
                    [
                        "height" => 5,
                        "width" => 20,
                        "length" => 20,
                        "weight" => 0.5
                    ]
                ]
            ];

            $response = Http::withToken($token)
                ->timeout(30)
                ->withHeaders(['Accept' => 'application/json'])
                ->post($url, $payload);

            if (!$response->successful()) {
                return ["error" => "Erro ao calcular o frete: " . $response->body()];
            }

            $data = $response->json();
            $opcaoFrete = collect($data)->first(fn($opcao) => isset($opcao["price"]));

            if (!$opcaoFrete) {
                return ["error" => "Nenhum serviço de frete disponível com preço válido."];
            }

            return [
                'valor' => $opcaoFrete["price"] / 100,
                'prazo' => $opcaoFrete["delivery_time"] ?? 5,
                'grátis' => false
            ];
        } catch (\Exception $e) {
            return ["error" => "Erro inesperado: " . $e->getMessage()];
        }
    }

    private function getCidadePorCep($cep)
    {
        $cep = preg_replace('/\D/', '', $cep);

        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        if ($response->successful()) {
            return $response->json()['localidade'] ?? '';
        }

        return '';
    }


}
