Tema Escolhido: 16 - Portal de Notícias Economia e Finanças Pessoais ("EcoFinanças")

🎯 Objetivo
Desenvolver um sistema completo de portal de notícias em PHP, aplicando os conceitos de desenvolvimento web vistos durante a disciplina. O projeto inclui CRUDs de usuários e notícias, autenticação, uso de banco de dados relacional e uma interface amigável.

🧩 Requisitos do Sistema Atendidos
🔐 Autenticação de Usuários
 Cadastro de novos usuários (cadastro.php)
 Login com verificação (login.php)
 Logout (logout.php)
 Edição de conta (editar_usuario.php)
 Exclusão de conta (excluir_usuario.php)
📰 Tabela de Notícias (dump.sql)
id INT (Chave primária, auto-incremento)
titulo VARCHAR (Obrigatório)
noticia TEXT (Obrigatório)
data DATETIME (Data/hora da publicação)
autor INT (Chave estrangeira referenciando usuarios.id)
imagem VARCHAR (Opcional)
🔗 Relacionamentos e Funcionalidades
Usuário: Gerenciamento completo. Só é possível cadastrar notícias logado.
Notícia: O campo autor referencia explicitamente o ID do usuário. Edição e exclusão (excluir_noticia.php) funcionais protegidas por autor.
Página Inicial: Exibe listagem pública filtrada por ordem de recência (data DESC), mostrando título, texto, autor e data formatados.
🎨 Estilização
Projeto completamente refatorado em Vanilla HTML e CSS (style.css), aplicando o design "Soft UI" utilizando tons de Esmeralda, vidros foscos interativos (glassmorphism) e curvas confortáveis, organizando perfeitamente a distinção entre áreas públicas e logadas.

📁 Estrutura de Arquivos
🏠 Páginas Públicas
index.php: Página inicial com listagem
noticia.php: Página individual para leitura da notícia
login.php: Formulário de login
cadastro.php: Formulário de criação de conta
logout.php: Encerrar a sessão do usuário
🔐 Área Restrita
dashboard.php: Painel central do portal
nova_noticia.php: Formulário para nova notícia
editar_noticia.php: Edição de notícias
excluir_noticia.php: Deleção protegida de notícias
editar_usuario.php: Atualização de credenciais de login
excluir_usuario.php: Ferramenta para encerrar sua existência na base de dados
🧠 Banco de Dados e Conexão
conexao.php: Conexão PDO com MySQL
funcoes.php: Requisitos genéricos
verifica_login.php: Bloqueio de acesso para convidados
📦 Entrega
Projeto integral disponível contendo os scripts de PHP, configurações visuais e o arquivo dump.sql atualizado para criar e popular o banco de testes. Nenhuma imagem avulsa está flutuando, todas restritas no diretório oficial imagens/.
