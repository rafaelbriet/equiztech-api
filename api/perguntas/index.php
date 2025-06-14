<?php

require('../autenticacao/functions.php');

only_admin_allowed();

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $response = create_question();
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = get_question_by_id($id);
        } else {
            $response = get_questions();
        }
        break;
    case 'PUT':
        $response = update_question();
        break;
    case 'DELETE':
        $response = delete_question();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function delete_question() {
    $question_id = $_GET['id'];

    try {
        $connection = create_connection();

        $query = $connection->prepare('DELETE FROM respostas WHERE id_pergunta = ?');
        $query->bind_param('i', $question_id);
        $query->execute();

        $query = $connection->prepare('DELETE FROM perguntas WHERE id = ?');
        $query->bind_param('i', $question_id);
        $query->execute();
        
        if ($query->affected_rows > 0) {
            $response = [];
        } else {
            $response = [
                'erro' => [ 'mensagem' => 'Não foi possivel encontrar uma categoria com o ID fornecido.' ]
            ];
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function update_question() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $question_id = $_GET['id'];

    try {
        $connection = create_connection();
        $query = $connection->prepare('UPDATE perguntas SET texto_pergunta = ? , explicacao = ?, ativo = ?, id_categoria = ? WHERE id = ?');
        $query->bind_param('ssiii', $data['pergunta']['texto_pergunta'], $data['pergunta']['explicacao'], $data['pergunta']['ativo'], $data['pergunta']['id_categoria'], $question_id);
        $query->execute();

        foreach ($data['pergunta']['respostas'] as $answer) {
            $query = $connection->prepare('UPDATE respostas SET texto_alternativa = ?, correta = ? WHERE id = ?');
            $query->bind_param('sii', $answer['texto_alternativa'], $answer['correta'], $answer['id']);
            $query->execute();
        }

        $response = [
            "pergunta" => get_question_by_id($question_id)
        ];

    } catch (\Throwable $th) {
        $response = [
            // 'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
            'erro' => [ 'mensagem' => $th->getMessage() ]
        ];
    }

    return $response;
}

function get_question_by_id($id) {
    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT perguntas.id, texto_pergunta, explicacao, ativo, id_categoria, categorias.nome AS nome_categoria FROM perguntas INNER JOIN categorias ON categorias.id = perguntas.id_categoria WHERE perguntas.id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $question = $result->fetch_assoc();
            $response = ['pergunta' => array_merge($question, get_answers_by_question_id($question['id']))];
        } else {
            $response = [
                'erro' => [ 'mensagem' => 'Não foi encontrado uma pergunta com o ID fornecido.' ]
            ];
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_questions() {
    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT perguntas.id, texto_pergunta, explicacao, ativo, id_categoria, categorias.nome AS nome_categoria FROM perguntas INNER JOIN categorias ON categorias.id = perguntas.id_categoria');
        $query->execute();
        $result = $query->get_result();
        $response = [ 'perguntas' => [] ];

        while ($row = $result->fetch_assoc()) {
            array_push($response['perguntas'], array_merge($row, get_answers_by_question_id($row['id'])));
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_answers_by_question_id($id) {
    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT id, texto_alternativa, correta FROM respostas WHERE id_pergunta = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            $response = [
                "respostas" => $result->fetch_all(MYSQLI_ASSOC)
            ];
        } else {
            $response = [
                'erro' => [ 'mensagem' => 'Não foi encontrado uma pergunta com o ID fornecido.' ]
            ];
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
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