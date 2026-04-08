<?php
/**
 * Configurações de Segurança do EcoFinanças
 * 
 * Este arquivo contém configurações sensíveis do sistema.
 * Mantenha este arquivo em local seguro e não o compartilhe.
 */

// ============================================
// SENHA DE AUTORIZAÇÃO PARA CADASTRO DE ADMIN
// ============================================
// Altere esta senha para controlar quem pode cadastrar administradores
// Recomendação: Use uma senha forte com letras, números e símbolos
define('SENHA_AUTORIZACAO_ADMIN', 'EcoFin2026!Admin');

// ============================================
// OUTRAS CONFIGURAÇÕES DE SEGURANÇA
// ============================================

// Tempo máximo de sessão (em segundos) - 2 horas
define('SESSAO_TEMPO_MAXIMO', 7200);

// Número máximo de tentativas de login
define('LOGIN_TENTATIVAS_MAXIMO', 5);

// Tempo de bloqueio após tentativas falhas (em segundos) - 15 minutos
define('LOGIN_BLOQUEIO_TEMPO', 900);

// ============================================
// INSTRUÇÕES PARA ALTERAR A SENHA DE ADMIN
// ============================================
/*
 * Para alterar a senha de autorização de admin:
 * 1. Edite este arquivo (config_seguranca.php)
 * 2. Altere o valor de SENHA_AUTORIZACAO_ADMIN
 * 3. Salve o arquivo
 * 
 * Exemplo:
 * define('SENHA_AUTORIZACAO_ADMIN', 'NovaSenhaForte123!');
 */
