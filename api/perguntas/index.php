<?php

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $response = create_question();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function create_question() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    try {
        $connection = create_connection();
        $query = $connection->prepare('INSERT INTO perguntas (texto_pergunta, explicacao, ativo, id_categoria) VALUES (?, ?, ?, ?)');
        $query->bind_param('ssii', $data['pergunta']['texto_pergunta'], $data['pergunta']['explicacao'], $data['pergunta']['ativo'], $data['pergunta']['id_categoria']);
        $query->execute();
        $data['pergunta']['id'] = $query->insert_id;

        foreach ($data['pergunta']['respostas'] as $key => $answer) {
            $query = $connection->prepare('INSERT INTO respostas (texto_alternativa, correta, id_pergunta) VALUES (?, ?, ?)');
            $query->bind_param('sii', $answer['texto_alternativa'], $answer['correta'], $data['pergunta']['id']);
            $query->execute();
            $data['pergunta']['respostas'][$key]['id'] = $query->insert_id;
        }

        $response = [
            "pergunta" => $data
        ];
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => $th->getMessage() ]
        ];
    }

    return $response;
}

header('Content-Type: application/json');
echo json_encode($response);