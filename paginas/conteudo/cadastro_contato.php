  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cadastro de Contatos</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-4">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Cadastrar contato</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" required placeholder="Digite o nome de contato">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" required placeholder="(00) 00000-0000">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Endereço de E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required placeholder="Digite um e-mail">
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputFile">Foto do contato</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" for="exampleInputFile">Arquivo de imagem</label>
                      </div>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="hidden" class="custom-file-input" name="id_user" id="id_user" value="<?php echo $id_user ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                    <label class="form-check-label" for="exampleCheck1">Autorizo o cadastro do meu contato</label>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="botao" class="btn btn-primary">Cadastrar Contato</button>
                </div>
              </form>
              <?php
include('../config/conexao.php'); // Inclui o arquivo de conexão com o banco de dados

// Verifica se o formulário foi enviado
if (isset($_POST['botao'])) {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Usando hash seguro para a senha

    // Verifica se foi enviado algum arquivo de foto
    if (!empty($_FILES['foto']['name'])) {
        $formatosPermitidos = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo

        // Verifica se a extensão do arquivo está nos formatos permitidos
        if (in_array(strtolower($extensao), $formatosPermitidos)) {
            $pasta = "img/"; // Define o diretório para upload
            $temporario = $_FILES['foto']['tmp_name']; // Caminho temporário do arquivo
            $novoNome = uniqid() . ".$extensao"; // Gera um nome único para o arquivo

            // Move o arquivo para o diretório de imagens
            if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                // Sucesso no upload da imagem
            } else {
                echo '<div class="container">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
                            Não foi possível fazer o upload do arquivo.
                        </div>
                    </div>';
                exit(); // Termina a execução do script após o erro
            }
        } else {
            echo '<div class="container">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Formato Inválido!</h5>
                        Formato de arquivo não permitido.
                    </div>
                </div>';
            exit(); // Termina a execução do script após o erro
        }
    } else {
        // Define um avatar padrão caso não seja enviado nenhum arquivo de foto
        $novoNome = 'avatar-padrao.png'; // Nome do arquivo de avatar padrão
    }

    // Prepara a consulta SQL para inserção dos dados do usuário
    $cadastro = "INSERT INTO tb_user (foto_user, nome_user, email_user, senha_user) VALUES (:foto, :nome, :email, :senha)";

    try {
        $result = $conect->prepare($cadastro);
        $result->bindParam(':nome', $nome, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':senha', $senha, PDO::PARAM_STR);
        $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);
        $result->execute();
        $contar = $result->rowCount();

        if ($contar > 0) {
            echo '<div class="container">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> OK!</h5>
                        Dados inseridos com sucesso !!!
                    </div>
                </div>';
        } else {
            echo '<div class="container">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Erro!</h5>
                        Dados não inseridos !!!
                    </div>
                </div>';
        }
    } catch (PDOException $e) {
        // Loga a mensagem de erro em vez de exibi-la para o usuário
        error_log("ERRO DE PDO: " . $e->getMessage());
        echo '<div class="container">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
                    Ocorreu um erro ao tentar inserir os dados.
                </div>
            </div>';
    }
}
?>
            </div>
</div>
            
            <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contatos Recentes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nome</th>
                      <th>Telefone</th>
                      <th>E-mail</th>
                      <th style="width: 40px">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                                      
                    <tr>
                      <td>1</td>
                      <td>Leandro</td>
                      <td>
                      85991446498
                      </td>
                      <td>
                      francisco.silva92@prof.ce.gov
                      </td>
                      <td>
                      <div class="btn-group">
                        <a href="home.php?acao=editar&id=" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></button>
                        <a href="conteudo/del-contato.php?idDel=" onclick="return confirm('Deseja remover o contato')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
                      </div>
                      </td>
                    </tr>
                   
                                       
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            </div>

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  