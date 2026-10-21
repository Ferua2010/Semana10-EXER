<?php

declare(strict_types=1);

function exibirBloco(string $titulo, string $valor): void
{
    echo "{$titulo}: {$valor}\n";
}

function inspecionarHash(string $hash): void
{
    $partes = explode('$', $hash);

    echo "Hash completo: {$hash}\n\n";

    if ($partes[1] === 'argon2id') {
        exibirBloco('Algoritmo utilizado', $partes[1]);
        exibirBloco('Versão do algoritmo', str_replace('v=', '', $partes[2]));
        exibirBloco('Custo de memória e tempo', $partes[3]);
        exibirBloco('Vetor de Salt em Base64', $partes[4]);
        exibirBloco('Hash final', $partes[5]);
        return;
    }

    exibirBloco('Algoritmo utilizado', $partes[1]);
    exibirBloco('Custo de processamento', $partes[2]);
    exibirBloco('Vetor de Salt em Base64', $partes[3]);
    exibirBloco('Hash final', $partes[4]);
}

$senha = readline('Digite uma senha: ');
$hash = password_hash($senha, PASSWORD_ARGON2ID);

inspecionarHash($hash);