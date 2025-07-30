<?php
function pegarDadosUsuario() {
    $usuario_nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); 
    $tipo_usuario_logado = htmlspecialchars($_SESSION['tipo_usuario'] ?? ''); 
    $iniciais_usuario = 'U';

    if (!empty($usuario_nome)) {
        $nome_partes = explode(' ', $usuario_nome); 
        if (count($nome_partes) >= 2) {
            $iniciais_usuario = strtoupper(substr($nome_partes[0], 0, 1) . substr($nome_partes[count($nome_partes) - 1], 0, 1));
        } elseif (count($nome_partes) === 1 && !empty($nome_partes[0])) {
            $iniciais_usuario = strtoupper(substr($nome_partes[0], 0, 1));
        }
    }

    return [
        'nome' => $usuario_nome,
        'tipo' => $tipo_usuario_logado,
        'iniciais' => $iniciais_usuario
    ];
}
?>