<?php
session_start();

// Se já estiver logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'leitor';
    
    // Campos adicionais
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $especialidade = $_POST['especialidade'] ?? '';
    $bio = trim($_POST['bio'] ?? '');
    $senha_admin = $_POST['senha_admin'] ?? '';

    // Incluir configurações de segurança
    require_once 'config_seguranca.php';

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = 'Todos os campos são obrigatórios.';
    } elseif (strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } elseif (!in_array($tipo, ['leitor', 'reporter', 'admin'])) {
        $erro = 'Tipo de usuário inválido.';
    } elseif ($tipo === 'admin' && empty($senha_admin)) {
        $erro = 'A senha de autorização é obrigatória para administradores.';
    } elseif ($tipo === 'admin' && $senha_admin !== SENHA_AUTORIZACAO_ADMIN) {
        $erro = 'Senha de autorização incorreta. Contate o responsável pelo sistema.';
    } elseif ($tipo === 'reporter' && empty($cpf)) {
        $erro = 'O CPF é obrigatório para repórteres.';
    } elseif ($tipo === 'reporter' && empty($telefone)) {
        $erro = 'O telefone é obrigatório para repórteres.';
    } else {
        require_once 'includes/conexao.php';

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            if ($tipo === 'admin') {
                // Inserção para admin
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$nome, $email, $senha_hash, $tipo])) {
                    $sucesso = 'Cadastro de administrador realizado com sucesso! Redirecionando...';
                } else {
                    $erro = 'Erro ao cadastrar. Tente novamente.';
                }
            } elseif ($tipo === 'reporter') {
                // Inserção para repórter com campos adicionais
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo, cpf, telefone, especialidade, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$nome, $email, $senha_hash, $tipo, $cpf, $telefone, $especialidade, $bio])) {
                    $sucesso = 'Cadastro de repórter realizado! Sua conta será analisada pela equipe editorial. Redirecionando...';
                } else {
                    $erro = 'Erro ao cadastrar. Tente novamente.';
                }
            } else {
                // Inserção para leitor (campos básicos)
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$nome, $email, $senha_hash, $tipo])) {
                    $sucesso = 'Cadastro realizado com sucesso! Redirecionando...';
                } else {
                    $erro = 'Erro ao cadastrar. Tente novamente.';
                }
            }
            
            if (empty($erro)) {
                header('refresh:2;url=login.php');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-card">
            <div class="cadastro-header">
                <div style="text-align: center; margin-bottom: 25px;">
                    <img src="imagens/logo-ecofinancas.png" alt="Logo" style="width: 100px; height: 100px; object-fit: contain;">
                </div>
                <h1>Criar Conta</h1>
                <p>Junte-se ao EcoFinanças e organize suas finanças</p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($erro) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($sucesso) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="cadastro-form">
                <div class="form-group">
                    <label for="nome" class="form-label">
                        <i class="fas fa-user"></i>
                        Nome Completo
                    </label>
                    <input type="text" id="nome" name="nome" class="form-control"
                        placeholder="Seu nome completo" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required minlength="3">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        E-mail
                    </label>
                    <input type="email" id="email" name="email" class="form-control"
                        placeholder="seu@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="senha" class="form-label">
                        <i class="fas fa-lock"></i>
                        Senha
                    </label>
                    <div class="password-input">
                        <input type="password" id="senha" name="senha" class="form-control"
                            placeholder="Mínimo 6 caracteres" required minlength="6" onkeyup="verificarForcaSenha()">
                        <button type="button" class="toggle-password" onclick="toggleSenha('senha', 'olho1')">
                            <i class="fas fa-eye" id="olho1"></i>
                        </button>
                    </div>
                    <div class="senha-forte">
                        <span id="texto-forca">Força da senha</span>
                        <div class="barra"><div class="progresso" id="progresso-senha"></div></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha" class="form-label">
                        <i class="fas fa-lock"></i>
                        Confirmar Senha
                    </label>
                    <div class="password-input">
                        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control"
                            placeholder="Repita a senha" required>
                        <button type="button" class="toggle-password" onclick="toggleSenha('confirmar_senha', 'olho2')">
                            <i class="fas fa-eye" id="olho2"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo" class="form-label">
                        <i class="fas fa-user-tag"></i>
                        Tipo de Usuário
                    </label>
                    <select id="tipo" name="tipo" class="form-control" onchange="toggleCamposTipo()">
                        <option value="leitor">Leitor</option>
                        <option value="reporter">Repórter (Publicar Notícias)</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <!-- Senha de autorização para Admin -->
                <div id="campo-senha-admin" style="display: none;">
                    <div class="alert alert-warning" style="background: rgba(245, 158, 11, 0.1); border-left: 3px solid var(--warning); color: var(--text-secondary); margin-bottom: 20px;">
                        <i class="fas fa-shield-alt"></i>
                        <span><strong>Área Restrita:</strong> É necessária a senha de autorização para cadastrar um administrador.</span>
                    </div>
                    <div class="form-group">
                        <label for="senha_admin" class="form-label">
                            <i class="fas fa-key"></i>
                            Senha de Autorização
                        </label>
                        <input type="password" id="senha_admin" name="senha_admin" class="form-control"
                            placeholder="Digite a senha de autorização">
                        <small style="color: var(--text-muted); font-size: 0.85rem;">
                            Solicite a senha ao responsável pelo sistema
                        </small>
                    </div>
                </div>

                <!-- Campos adicionais para Repórter -->
                <div id="campos-reporter" style="display: none;">
                    <div class="form-divider">
                        <h3 style="font-size: 1.1rem; margin-bottom: 20px; color: var(--primary-light);">
                            <i class="fas fa-id-card"></i> Informações de Repórter
                        </h3>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cpf" class="form-label">
                                <i class="fas fa-id-badge"></i>
                                CPF
                            </label>
                            <input type="text" id="cpf" name="cpf" class="form-control"
                                placeholder="000.000.000-00" value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" 
                                oninput="mascararCPF(this)">
                        </div>

                        <div class="form-group">
                            <label for="telefone" class="form-label">
                                <i class="fas fa-phone"></i>
                                Telefone
                            </label>
                            <input type="tel" id="telefone" name="telefone" class="form-control"
                                placeholder="(00) 00000-0000" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>"
                                oninput="mascararTelefone(this)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="especialidade" class="form-label">
                            <i class="fas fa-briefcase"></i>
                            Área de Especialidade
                        </label>
                        <select id="especialidade" name="especialidade" class="form-control">
                            <option value="">Selecione sua área</option>
                            <option value="economia">Economia</option>
                            <option value="mercado_financeiro">Mercado Financeiro</option>
                            <option value="investimentos">Investimentos</option>
                            <option value="financas_pessoais">Finanças Pessoais</option>
                            <option value="criptomoedas">Criptomoedas</option>
                            <option value="politica_economica">Política Econômica</option>
                            <option value="bolsa_valores">Bolsa de Valores</option>
                            <option value="geral">Geral</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bio" class="form-label">
                            <i class="fas fa-align-left"></i>
                            Biografia Profissional
                        </label>
                        <textarea id="bio" name="bio" class="form-control" rows="4"
                            placeholder="Conte um pouco sobre sua experiência como jornalista ou escritor financeiro..."
                            maxlength="500"><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
                        <small style="color: var(--text-muted); font-size: 0.85rem;">
                            Máximo 500 caracteres
                        </small>
                    </div>

                    <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border-left: 3px solid var(--primary); color: var(--text-secondary);">
                        <i class="fas fa-info-circle"></i>
                        <span>Sua conta de repórter será analisada pela equipe editorial antes de ser aprovada.</span>
                    </div>
                </div>

                <div class="termos">
                    <input type="checkbox" id="termos" name="termos" required>
                    <label for="termos">
                        Li e concordo com os <a href="#">Termos de Uso</a> e <a href="#">Política de Privacidade</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-success btn-cadastro">
                    <i class="fas fa-user-plus"></i> Criar Conta
                </button>
            </form>

            <div class="cadastro-footer">
                <p>Já tem uma conta? <a href="login.php">Fazer Login</a></p>
            </div>
        </div>
    </div>

    <script>
        function toggleSenha(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function verificarForcaSenha() {
            const senha = document.getElementById('senha').value;
            const progresso = document.getElementById('progresso-senha');
            const texto = document.getElementById('texto-forca');
            let forca = 0;
            if (senha.length >= 6) forca++;
            if (senha.length >= 10) forca++;
            if (/[A-Z]/.test(senha)) forca++;
            if (/[0-9]/.test(senha)) forca++;
            if (/[^A-Za-z0-9]/.test(senha)) forca++;
            progresso.className = 'progresso';
            if (forca <= 2) {
                progresso.classList.add('fraca');
                texto.textContent = 'Senha fraca';
                texto.style.color = '#e53935';
            } else if (forca <= 4) {
                progresso.classList.add('media');
                texto.textContent = 'Senha média';
                texto.style.color = '#fb8c00';
            } else {
                progresso.classList.add('forte');
                texto.textContent = 'Senha forte';
                texto.style.color = '#43a047';
            }
        }

        function toggleCamposTipo() {
            const tipo = document.getElementById('tipo').value;
            const camposReporter = document.getElementById('campos-reporter');
            const campoSenhaAdmin = document.getElementById('campo-senha-admin');
            
            // Esconder todos primeiro
            camposReporter.style.display = 'none';
            campoSenhaAdmin.style.display = 'none';
            
            // Limpar campos obrigatórios
            document.getElementById('senha_admin').required = false;
            
            if (tipo === 'reporter') {
                camposReporter.style.display = 'block';
            } else if (tipo === 'admin') {
                campoSenhaAdmin.style.display = 'block';
                document.getElementById('senha_admin').required = true;
            }
        }

        function mascararCPF(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            input.value = value;
        }

        function mascararTelefone(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length > 6) {
                value = value.replace(/(\d{2})(\d{5})(\d)/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
            }
            input.value = value;
        }

        // Verificar se deve mostrar campos ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            toggleCamposTipo();
        });
    </script>
</body>
</html>