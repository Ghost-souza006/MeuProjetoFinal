# 🛡️ Sistema de Administração - EcoFinanças

## Visão Geral

O EcoFinanças possui um sistema de permissões baseado em **3 níveis de usuário**, com funcionalidades específicas para cada tipo.

---

## 👥 Tipos de Usuário

### 1. 📖 Leitor
**Permissões:**
- ✅ Visualizar notícias no portal
- ✅ Navegar pelo site
- ❌ Publicar notícias
- ❌ Gerenciar usuários

**Cadastro:** Simples (nome, email, senha)

---

### 2. 📝 Repórter
**Permissões:**
- ✅ Tudo que o Leitor pode fazer
- ✅ Publicar notícias
- ✅ Editar suas próprias notícias
- ✅ Excluir suas próprias notícias
- ❌ Gerenciar outros usuários
- ❌ Excluir notícias de outros

**Cadastro:** Requer informações adicionais
- CPF
- Telefone
- Área de especialidade
- Biografia profissional

**Fluxo de aprovação:** Conta é analisada pela equipe editorial antes de ativar

---

### 3. 👑 Administrador
**Permissões:**
- ✅ Tudo que o Leitor pode fazer
- ✅ Tudo que o Repórter pode fazer
- ✅ **Gerenciar TODOS os usuários** (editar e excluir)
- ✅ **Gerenciar TODAS as notícias** (editar e excluir qualquer uma)
- ✅ Visualizar lista completa de usuários
- ✅ Visualizar estatísticas do sistema
- ✅ Cadastrar novos administradores (com senha de autorização)

**Cadastro:** Requer **senha de autorização** especial

---

## 🔐 Senha de Autorização para Admin

Para proteger contra cadastros não autorizados de administradores, o sistema utiliza uma **senha mestre**.

### 🔑 Senha Padrão
```
EcoFin2026!Admin
```

### ⚙️ Como Alterar a Senha

1. Abra o arquivo: `config_seguranca.php`
2. Localize a linha:
   ```php
   define('SENHA_AUTORIZACAO_ADMIN', 'EcoFin2026!Admin');
   ```
3. Altere para sua senha forte:
   ```php
   define('SENHA_AUTORIZACAO_ADMIN', 'NovaSenhaForte123!@#');
   ```
4. Salve o arquivo

### 📋 Campos Específicos por Tipo

| Campo | Leitor | Repórter | Admin |
|-------|--------|----------|-------|
| Nome | ✅ | ✅ | ✅ |
| Email | ✅ | ✅ | ✅ |
| Senha | ✅ | ✅ | ✅ |
| Confirmar Senha | ✅ | ✅ | ✅ |
| Senha de Autorização | ❌ | ❌ | ✅ |
| CPF | ❌ | ✅ | ❌ |
| Telefone | ❌ | ✅ | ❌ |
| Especialidade | ❌ | ✅ | ❌ |
| Biografia | ❌ | ✅ | ❌ |

---

## 📊 Painel Administrativo

### Funcionalidades do Admin no Dashboard

#### 1. Gerenciar Usuários (`gerenciar_usuarios.php`)
- ✅ Visualizar todos os usuários cadastrados
- ✅ Ver estatísticas por tipo (Admin, Repórter, Leitor)
- ✅ Editar dados de qualquer usuário
- ✅ Excluir usuários (exceto a si mesmo)
- ✅ Tabela com filtros e ações rápidas

#### 2. Gerenciar Notícias (`gerenciar_noticias.php`)
- ✅ Visualizar todas as notícias do portal
- ✅ Ver autor de cada notícia
- ✅ Editar qualquer notícia
- ✅ Excluir qualquer notícia
- ✅ Visualizar notícias em grid

#### 3. Publicar Notícias (`nova_noticia.php`)
- ✅ Criar novas notícias como admin
- ✅ As notícias aparecem com seu nome como autor

---

## 🗂️ Estrutura de Arquivos Admin

```
admin/
├── dashboard.php              # Painel principal
├── gerenciar_usuarios.php     # Gestão de usuários (Admin only)
├── gerenciar_noticias.php     # Gestão de notícias (Admin only)
├── nova_noticia.php           # Criar notícia
├── editar_noticia.php         # Editar notícia
├── editar_usuario.php         # Editar perfil
├── excluir_noticia.php        # Excluir notícia
└── excluir_usuario.php        # Excluir usuário (Admin only)
```

---

## 🚀 Fluxos de Trabalho

### Cadastro de Admin
```
1. Acessar /cadastro.php
2. Preencher nome, email, senha
3. Selecionar "Administrador"
4. Inserir Senha de Autorização
5. Sistema valida senha
6. Se correta → Admin cadastrado
7. Se incorreta → Erro de autorização
```

### Cadastro de Repórter
```
1. Acessar /cadastro.php
2. Preencher dados básicos
3. Selecionar "Repórter"
4. Preencher campos adicionais (CPF, telefone, etc.)
5. Sistema salva com status pendente
6. Admin analisa e aprova manualmente
```

### Exclusão de Usuário (Admin)
```
1. Admin acessa "Gerenciar Usuários"
2. Clica no ícone de lixeira
3. Sistema pede confirmação
4. Se confirmado:
   - Exclui todas as notícias do usuário
   - Exclui o usuário
5. Redireciona de volta com mensagem
```

### Exclusão de Notícia
```
Admin:
1. Pode excluir de qualquer lugar
2. Dashboard próprio ou "Gerenciar Notícias"
3. Confirmação → Exclusão imediata

Repórter:
1. Só pode excluir suas próprias notícias
2. Sistema verifica autorização
3. Confirmação → Exclusão
```

---

## 🔒 Regras de Segurança

### ✅ Proteções Implementadas

1. **Senha de Autorização**
   - Necessária para cadastrar admins
   - Armazenada em arquivo separado
   - Fácil de alterar

2. **Auto-Proteção**
   - Admin não pode excluir a si mesmo
   - Sistema previne acidentes

3. **Verificação de Permissões**
   - Cada ação verifica o tipo de usuário
   - Redirecionamento automático se não autorizado

4. **Confirmação de Ações**
   - Exclusões pedem confirmação
   - Mensagens claras de aviso

5. **Cascade Delete**
   - Ao excluir usuário, suas notícias também são excluídas
   - Mantém integridade do banco

### ⚠️ Recomendações

- [ ] Alterar senha de autorização padrão antes de produção
- [ ] Fazer backup regular do banco de dados
- [ ] Revisar contas de admin periodicamente
- [ ] Manter `config_seguranca.php` em local seguro
- [ ] Não compartilhar credenciais de admin
- [ ] Usar HTTPS em produção
- [ ] Implementar log de auditoria (opcional)

---

## 📈 Estatísticas Disponíveis

O dashboard admin mostra:
- Total de usuários
- Total de administradores
- Total de repórteres
- Total de leitores
- Total de notícias no portal
- Suas próprias notícias

---

##  Solução de Problemas

### "Não consigo acessar o painel admin"
- Verifique se está logado como admin
- Confirme que `usuario_tipo = 'admin'` no banco

### "Senha de autorização não funciona"
- Verifique o valor em `config_seguranca.php`
- A senha padrão é: `EcoFin2026!Admin`

### "Não posso excluir um usuário"
- Admin não pode excluir a si mesmo
- Verifique se tem permissões de admin

### "Repórter não consegue publicar"
- Conta pode estar pendente de aprovação
- Verifique o campo `tipo` no banco

---

## 📞 Suporte

Para dúvidas ou problemas com o sistema de administração:
- Verifique este documento
- Consulte o arquivo `SEGURANCA.md`
- Revise os logs de erro do PHP
