<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FreteService
{
    public function calcularFrete($cepDestino)
    {

        $cepOrigem = "61913080"; // CEP da sua loja ou origem do envio

        $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";

        $params = [
            "nCdEmpresa" => "",
            "sDsSenha" => "",
            "nCdServico" => "04014", // SEDEX, pode mudar conforme necessário
            "sCepOrigem" => $cepOrigem,
            "sCepDestino" => $cepDestino,
            "nVlPeso" => 0.5,
            "nCdFormato" => 1,
            "nVlComprimento" => 20,
            "nVlAltura" => 5,
            "nVlLargura" => 20,
            "nVlDiametro" => 0,
            "sCdMaoPropria" => "n",
            "nVlValorDeclarado" => 0,
            "sCdAvisoRecebimento" => "n",
            "StrRetorno" => "xml",
        ];

        $response = Http::timeout(30)->get($url, $params);

        if (!$response->successful()) {
            return ["error" => "Erro ao conectar com os Correios."];
        }

        $xml = simplexml_load_string($response->body());

        // Depuração: Verifique o XML retornado
        if (!isset($xml->cServico->Valor)) {
            return ["error" => "Não foi possível calcular o frete."];
        }

        return [
            "valor" => (string) $xml->cServico->Valor,
            "prazo" => (string) $xml->cServico->PrazoEntrega
        ];
    }
}
