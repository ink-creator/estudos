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
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $id_user = $_POST['id_user'] ?? null;
    $novoNome = 'avatar-padrao.png';

    // Verifica se foi enviado algum arquivo de foto
    if (!empty($_FILES['foto']['name'])) {
        $formatosPermitidos = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo

        // Verifica se a extensão do arquivo está nos formatos permitidos
        if (in_array(strtolower($extensao), $formatosPermitidos)) {
            $pasta = __DIR__ . '/../../img/cont/'; // Define o diretório real para upload do contato
            $temporario = $_FILES['foto']['tmp_name']; // Caminho temporário do arquivo
            $novoNome = uniqid() . ".$extensao"; // Gera um nome único para o arquivo

            // Move o arquivo para o diretório de imagens
            if (!move_uploaded_file($temporario, $pasta . $novoNome)) {
                echo '<div class="container">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
                            Não foi possível fazer o upload do arquivo.
                        </div>
                    </div>';
                exit();
            }
        } else {
            echo '<div class="container">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Formato Inválido!</h5>
                        Formato de arquivo não permitido.
                    </div>
                </div>';
            exit();
        }
    }

    // Prepara a consulta SQL para inserção do contato na tabela correta
    $cadastro = "INSERT INTO tb_contatos (nome_contatos, fone_contatos, email_contatos, foto_contatos, id_user) VALUES (:nome, :telefone, :email, :foto, :id_user)";

    try {
        $result = $conect->prepare($cadastro);
        $result->bindParam(':nome', $nome, PDO::PARAM_STR);
        $result->bindParam(':telefone', $telefone, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
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
                <table id="example1" class="table table-bordered table-hover">
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
                    
                   <?php
                    // PASSO 1: Seleciona todos os contatos em ordem decrescente
                    $select = "SELECT * FROM tb_contatos ORDER BY id_contatos DESC";

                    try {
                        $result = $conect->prepare($select);
                        $cont = 1;
                        $result->execute();

                        // PASSO 2: Verifica se o retorno contém registros
                        $contar = $result->rowCount();
                        if ($contar > 0) {
                            // PASSO 3: Percorre cada objeto de contato retornado
                            while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
                    ?>
                            <tr>
                                <td><?php echo $cont++;?></td>
                                <td><?php echo $show->nome_contatos;?></td>
                                <td><?php echo $show->fone_contatos;?></td>
                                <td><?php echo $show->email_contatos;?></td>
                              <td>
                                <div class="btn-group">
                                <a href="home.php?acao=editar&id=<?php echo $show->id_contatos;?>" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></a>
                                <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos;?>" onclick="return confirm('Deseja remover o contato')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
                                </div>
                                </td>
                            </tr>
                    <?php
                            }
                        }
                    } catch (PDOException $e) {
                        echo '<strong>ERRO DE PDO= </strong>' . $e->getMessage();
                    }
                    ?>
                    
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
  