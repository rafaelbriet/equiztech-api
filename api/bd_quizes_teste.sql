-- Inserindo categorias
INSERT INTO categorias (nome) VALUES 
('Fundamentos de Programação'),
('Desenvolvimento Web'),
('Lógica de Programação');

-- Inserindo perguntas e respostas para Fundamentos de Programação
INSERT INTO perguntas (texto_pergunta, explicacao, ativo, id_categoria) VALUES 
('O que é uma variável?', 'Uma variável é um espaço na memória que armazena um valor que pode ser alterado durante a execução do programa.', 1, 1),
('Qual é a função de um loop?', 'Um loop permite a repetição de um bloco de código enquanto uma condição é verdadeira.', 1, 1),
('O que é um array?', 'Um array é uma estrutura de dados que armazena uma coleção de elementos do mesmo tipo.', 1, 1),
('Qual é a saída de 2 + 2 em Python?', 'A operação de soma simples em Python retorna 4.', 1, 1),
('Para que serve a função print() em Python?', 'A função print() é usada para exibir uma mensagem ou valor na tela.', 1, 1),
('O que é um operador lógico?', 'Operadores lógicos são usados para combinar duas ou mais condições, como AND, OR e NOT.', 1, 1),
('Qual é a diferença entre == e = em programação?', 'O == é usado para comparar valores, enquanto o = é usado para atribuir um valor a uma variável.', 1, 1),
('O que significa IDE?', 'IDE significa "Integrated Development Environment", que é um ambiente de desenvolvimento que facilita a escrita de código.', 1, 1),
('O que é um bug em programação?', 'Um bug é um erro ou falha no código que impede o programa de funcionar corretamente.', 1, 1),
('O que é um compilador?', 'Um compilador traduz o código-fonte para um código que a máquina pode entender e executar.', 1, 1);

-- Inserindo respostas para Fundamentos de Programação
INSERT INTO respostas (texto_alternativa, correta, id_pergunta) VALUES 
('Um tipo de dado que nunca muda', 0, 1),
('Uma função que calcula números', 0, 1),
('Um espaço na memória para armazenar valores', 1, 1),
('Uma estrutura de repetição', 0, 1),
('Executar uma operação matemática', 0, 2),
('Repetir um bloco de código várias vezes', 1, 2),
('Parar a execução de um programa', 0, 2),
('Criar uma variável', 0, 2),
('Uma coleção de elementos do mesmo tipo', 1, 3),
('Uma variável que não muda', 0, 3),
('Uma função que ordena números', 0, 3),
('Um tipo de dado numérico', 0, 3),
('22', 0, 4),
('4', 1, 4),
('2', 0, 4),
('8', 0, 4),
('Para calcular valores', 0, 5),
('Para criar loops', 0, 5),
('Para exibir informações na tela', 1, 5),
('Para declarar variáveis', 0, 5),
('São símbolos usados para comparar números', 0, 6),
('São usados para criar variáveis', 0, 6),
('Combinam condições em operações lógicas', 1, 6),
('Permitem criar loops', 0, 6),
('Ambos são usados para comparar', 0, 7),
('== é para atribuição e = é para comparação', 0, 7),
('== compara valores e = atribui valores', 1, 7),
('Ambos são usados para criar variáveis', 0, 7),
('Integrated Data Environment', 0, 8),
('Internet Development Environment', 0, 8),
('Integrated Development Environment', 1, 8),
('Interface Development Extension', 0, 8),
('Uma funcionalidade de um programa', 0, 9),
('Um erro ou falha no código', 1, 9),
('Uma forma de otimizar um programa', 0, 9),
('Uma ferramenta de desenvolvimento', 0, 9),
('Um programa para escrever textos', 0, 10),
('Um software que traduz código para linguagem de máquina', 1, 10),
('Um tipo de variável', 0, 10),
('Um banco de dados', 0, 10);

-- Inserindo perguntas e respostas para Desenvolvimento Web
INSERT INTO perguntas (texto_pergunta, explicacao, ativo, id_categoria) VALUES 
('O que é HTML?', 'HTML (HyperText Markup Language) é a linguagem usada para criar a estrutura das páginas web.', 1, 2),
('Para que serve o CSS?', 'CSS (Cascading Style Sheets) é usado para estilizar e definir o layout de páginas web.', 1, 2),
('O que significa JavaScript?', 'JavaScript é uma linguagem de programação que permite criar interatividade em páginas web.', 1, 2),
('Qual é a função de uma tag <a> em HTML?', 'A tag <a> é usada para criar links para outras páginas ou seções da mesma página.', 1, 2),
('O que é um framework?', 'Um framework é uma estrutura que facilita o desenvolvimento de aplicações ao oferecer ferramentas e bibliotecas prontas.', 1, 2),
('Qual é a função do HTTP?', 'HTTP (HyperText Transfer Protocol) é o protocolo de comunicação usado na web para transferir dados entre cliente e servidor.', 1, 2),
('O que é um servidor web?', 'Um servidor web é um software que entrega páginas web ao navegador do usuário.', 1, 2),
('Qual é a diferença entre frontend e backend?', 'Frontend é a parte visível de um site, enquanto backend é a parte que roda no servidor.', 1, 2),
('Para que serve o atributo alt em uma tag <img>?', 'O atributo alt fornece uma descrição alternativa de uma imagem, usada quando ela não pode ser exibida.', 1, 2),
('O que é uma API?', 'API (Application Programming Interface) é um conjunto de regras que permite que programas diferentes se comuniquem.', 1, 2);

-- Inserindo respostas para Desenvolvimento Web
INSERT INTO respostas (texto_alternativa, correta, id_pergunta) VALUES 
('Uma linguagem de programação', 0, 11),
('Um banco de dados', 0, 11),
('Uma linguagem de marcação para estruturar páginas web', 1, 11),
('Um servidor de email', 0, 11),
('Para definir a estrutura de um site', 0, 12),
('Para criar a lógica do site', 0, 12),
('Para estilizar e definir o layout de páginas', 1, 12),
('Para armazenar dados do usuário', 0, 12),
('Uma biblioteca de estilos', 0, 13),
('Um banco de dados', 0, 13),
('Uma linguagem de programação para interatividade', 1, 13),
('Um protocolo de internet', 0, 13),
('Criar listas numeradas', 0, 14),
('Criar links para outras páginas', 1, 14),
('Exibir imagens', 0, 14),
('Formatar textos', 0, 14),
('Uma linguagem de programação', 0, 15),
('Uma biblioteca de dados', 0, 15),
('Uma estrutura para desenvolvimento de aplicações', 1, 15),
('Uma forma de armazenar dados', 0, 15),
('Enviar emails', 0, 16),
('Proteger sites contra hackers', 0, 16),
('Transferir dados entre cliente e servidor', 1, 16),
('Armazenar dados em um banco de dados', 0, 16),
('Um software que cria imagens', 0, 17),
('Um programa que lê e escreve arquivos', 0, 17),
('Um software que entrega páginas web', 1, 17),
('Uma extensão do navegador', 0, 17),
('Frontend é o servidor, backend é o usuário', 0, 18),
('Frontend é a parte visível e backend é o servidor', 1, 18),
('Ambos se referem ao design do site', 0, 18),
('Ambos são linguagens de programação', 0, 18),
('Para melhorar o SEO de uma página', 0, 19),
('Para aumentar a resolução da imagem', 0, 19),
('Para fornecer uma descrição alternativa da imagem', 1, 19),
('Para mudar o tamanho da imagem', 0, 19),
('Um sistema operacional', 0, 20),
('Um tipo de servidor web', 0, 20),
('Um conjunto de regras para comunicação entre programas', 1, 20),
('Um método de autenticação de usuários', 0, 20);

-- Inserindo perguntas e respostas para Lógica de Programação
INSERT INTO perguntas (texto_pergunta, explicacao, ativo, id_categoria) VALUES 
('O que é um algoritmo?', 'Um algoritmo é uma sequência de passos para resolver um problema.', 1, 3),
('Qual é a finalidade de um diagrama de fluxo?', 'Um diagrama de fluxo é usado para representar graficamente a lógica de um algoritmo.', 1, 3),
('O que é um loop infinito?', 'Um loop infinito ocorre quando a condição de término de um loop nunca é alcançada, causando sua execução sem fim.', 1, 3),
('O que significa depuração?', 'Depuração é o processo de encontrar e corrigir erros em um código.', 1, 3),
('O que é uma condição em programação?', 'Uma condição é uma expressão que retorna verdadeiro ou falso e determina a execução de um bloco de código.', 1, 3),
('O que é recursão?', 'Recursão é uma técnica em que uma função chama a si mesma para resolver um problema.', 1, 3),
('Para que serve a estrutura condicional if?', 'A estrutura if permite executar um bloco de código apenas se uma condição for verdadeira.', 1, 3),
('O que é um pseudocódigo?', 'Um pseudocódigo é uma forma simplificada de descrever algoritmos, utilizando uma linguagem mais próxima da linguagem natural.', 1, 3),
('Qual é a diferença entre while e for?', 'A diferença é que while é usado quando o número de repetições não é conhecido, e for é usado quando o número de repetições é definido.', 1, 3),
('O que é uma variável boolean?', 'Uma variável boolean armazena apenas dois valores: verdadeiro (true) ou falso (false).', 1, 3);

-- Inserindo respostas para Lógica de Programação
INSERT INTO respostas (texto_alternativa, correta, id_pergunta) VALUES 
('Uma variável complexa', 0, 21),
('Uma sequência de passos para resolver problemas', 1, 21),
('Uma função de uma linguagem', 0, 21),
('Um tipo de dado', 0, 21),
('Representar a memória de um programa', 0, 22),
('Criar variáveis complexas', 0, 22),
('Representar graficamente a lógica de um algoritmo', 1, 22),
('Aumentar a eficiência do código', 0, 22),
('Um loop que executa uma vez', 0, 23),
('Um loop que nunca termina', 1, 23),
('Um loop que ocorre esporadicamente', 0, 23),
('Um loop que executa duas vezes', 0, 23),
('Executar código automaticamente', 0, 24),
('Escrever código rapidamente', 0, 24),
('Encontrar e corrigir erros em um código', 1, 24),
('Criar novas funções', 0, 24),
('Um tipo de dado', 0, 25),
('Uma variável de memória', 0, 25),
('Uma expressão que retorna verdadeiro ou falso', 1, 25),
('Um tipo de função', 0, 25),
('Uma técnica para criar variáveis', 0, 26),
('Uma função para armazenar dados', 0, 26),
('Uma técnica em que uma função chama a si mesma', 1, 26),
('Um método de ordenação', 0, 26),
('Para criar loops', 0, 27),
('Para definir variáveis', 0, 27),
('Para executar código com base em uma condição', 1, 27),
('Para otimizar funções', 0, 27),
('Um código que é executado diretamente pelo computador', 0, 28),
('Um código escrito para ser lido apenas por desenvolvedores', 0, 28),
('Uma forma simplificada de descrever algoritmos', 1, 28),
('Uma linguagem de programação específica', 0, 28),
('Ambos são usados para loops com número fixo de repetições', 0, 29),
('While é para loops definidos e for para indefinidos', 0, 29),
('While é usado para repetições indefinidas e for para definidas', 1, 29),
('Ambos têm a mesma função', 0, 29),
('Armazenar números decimais', 0, 30),
('Armazenar strings', 0, 30),
('Armazenar valores verdadeiro ou falso', 1, 30),
('Armazenar listas', 0, 30);
