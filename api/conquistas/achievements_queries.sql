-- Active: 1729545499167@@127.0.0.1@3307@equiztech_api
-- Total de partidas que um jogador realizou
SELECT COUNT(*) AS total_partidas FROM partidas WHERE id_usuario = 1;

-- Total de resposta que um jogador deu
SELECT COUNT(*) AS total_respostas FROM respostas_partida
INNER JOIN partidas on partidas.id = respostas_partida.id_partida
WHERE partidas.id_usuario = 1;

-- Total de respostas que um jogador acertou
SELECT COUNT(*) AS total_respostas_correta FROM respostas_partida
INNER JOIN partidas on partidas.id = respostas_partida.id_partida
INNER JOIN respostas on respostas.id = respostas_partida.id_resposta_escolhida
WHERE partidas.id_usuario = 1 AND respostas.correta = 1;

-- Maior quantidade de partidas jogadas em um dia
WITH
    partidas(data_partida) AS (
        select CAST(iniciada_em AS DATE) AS data_partida
        FROM partidas
        WHERE id_usuario = 1
        ORDER BY data_partida
    ),
    partidas_por_dia AS (
        SELECT
            data_partida, 
            COUNT(*) AS total_partidas
        FROM partidas
        GROUP BY data_partida
    )
SELECT MAX(total_partidas) AS maior_quantidade_partida_dia
FROM partidas_por_dia;

-- Sequencia atual de dias com partidas jogadas

-- Maior sequencia de dias consecutivos com partidas jogadas
-- FONTE: https://blog.jooq.org/how-to-find-the-longest-consecutive-series-of-events-in-sql/
WITH 
    datas(date) AS (
        SELECT DISTINCT CAST(iniciada_em AS DATE) AS data_inicio
        FROM partidas
        WHERE id_usuario = 1
        ORDER BY 1
    ),
    grupos AS (
        SELECT
            ROW_NUMBER() OVER (ORDER BY date) as rn,
            DATE_ADD(date, INTERVAL -ROW_NUMBER() OVER (ORDER BY date) DAY) as grupo,
            date
        FROM
            datas
    ),
    sequencias AS (
        SELECT
            COUNT(*) as datas_consecutivas
        FROM grupos
        GROUP BY grupo
    )
SELECT MAX(datas_consecutivas)
FROM sequencias;