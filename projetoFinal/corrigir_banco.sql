-- Script para corrigir a estrutura da tabela noticias
-- Executar no phpMyAdmin ou via linha de comando

USE portal_financas;

-- Renomear autor_id para autor (se autor_id existir)
ALTER TABLE noticias CHANGE COLUMN IF EXISTS autor_id autor INT(11) NOT NULL;

-- Renomear conteudo para noticia (se conteudo existir)
ALTER TABLE noticias CHANGE COLUMN IF EXISTS conteudo noticia TEXT NOT NULL;

-- Renomear data_publicacao para data (se data_publicacao existir)
ALTER TABLE noticias CHANGE COLUMN IF EXISTS data_publicacao data DATETIME DEFAULT current_timestamp();

-- Verificar estrutura final
DESCRIBE noticias;
