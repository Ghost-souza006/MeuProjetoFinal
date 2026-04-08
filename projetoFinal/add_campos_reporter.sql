-- Script para adicionar campos de repórter na tabela usuarios
USE portal_financas;

-- Adicionar campos para informações de repórteres
ALTER TABLE usuarios 
ADD COLUMN telefone VARCHAR(20) DEFAULT NULL AFTER tipo,
ADD COLUMN cpf VARCHAR(14) DEFAULT NULL AFTER telefone,
ADD COLUMN bio TEXT DEFAULT NULL AFTER cpf,
ADD COLUMN especialidade VARCHAR(100) DEFAULT NULL AFTER bio,
ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL AFTER especialidade;

-- Verificar estrutura
DESCRIBE usuarios;
