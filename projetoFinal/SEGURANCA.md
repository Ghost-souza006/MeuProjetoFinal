# 🔐 Segurança do EcoFinanças

## Senha de Autorização para Administradores

Para proteger o sistema contra cadastros não autorizados de administradores, foi implementada uma **senha de autorização**.

### 📋 Como Funciona

1. Quando um usuário seleciona **"Administrador"** no cadastro, um campo extra aparece solicitando a **senha de autorização**
2. Sem essa senha, não é possível completar o cadastro de um admin
3. A senha é verificada no servidor antes de permitir o cadastro

### 🔑 Senha Padrão

```
EcoFin2026!Admin
```

### ⚙️ Como Alterar a Senha

1. Abra o arquivo `config_seguranca.php`
2. Localize a linha:
   ```php
   define('SENHA_AUTORIZACAO_ADMIN', 'EcoFin2026!Admin');
   ```
3. Altere o valor para uma senha forte:
   ```php
   define('SENHA_AUTORIZACAO_ADMIN', 'SuaNovaSenhaForte123!@#');
   ```
4. Salve o arquivo

### 💡 Recomendações de Segurança

- ✅ Use senhas com no mínimo 12 caracteres
- ✅ Combine letras maiúsculas, minúsculas, números e símbolos
- ✅ Altere a senha padrão antes de colocar o sistema em produção
- ✅ Mantenha o arquivo `config_seguranca.php` em local seguro
- ✅ Não compartilhe a senha publicamente
- ✅ Altere periodicamente a senha

### 🛡️ Níveis de Usuário

| Tipo | Descrição | Requer Senha de Autorização |
|------|-----------|----------------------------|
| **Leitor** | Pode apenas visualizar notícias | ❌ Não |
| **Repórter** | Pode publicar e editar notícias | ❌ Não (mas requer análise) |
| **Administrador** | Acesso total ao sistema | ✅ **Sim** |

### 📝 Fluxo de Cadastro

```
┌─────────────────┐
│ Selecionar Tipo │
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
  Leitor    Repórter    Admin
    │         │          │
    │    ┌────┴────┐     │
    │    │ Campos  │     │
    │    │ Adicionais│   │
    │    └────┬────┘     │
    │         │          │
    │         │     ┌────┴─────┐
    │         │     │ Senha    │
    │         │     │ Autoriz. │
    │         │     └────┬─────
    │         │          │
    └────┬────┴────┬─────┘
         │         │
    ┌────┴─────────┴────┐
    │   Cadastro        │
    │   Concluído       │
    └───────────────────┘
```

### 🚨 Importante

- A senha de autorização é **diferente** da senha de login do administrador
- Esta senha serve apenas para **autorizar o cadastro** de novos admins
- Após o cadastro, o admin usa sua senha pessoal para fazer login
