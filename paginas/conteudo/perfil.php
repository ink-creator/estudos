  <?php
  $mensagemPerfil = '';
  $tipoMensagemPerfil = 'danger';

  if (isset($_POST['upPerfil'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senhaNova = $_POST['senha'] ?? '';
    $idPerfil = (int) ($id_user ?? $_SESSION['senhaUser'] ?? 0);
    $fotoAntiga = $foto_user ?? '';
    $fotoNova = $fotoAntiga;
    $arquivoNovo = null;

    if ($idPerfil <= 0 || $nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $mensagemPerfil = 'Informe um nome e um endereço de e-mail válido.';
    } else {
      try {
        $consultaSenha = $conect->prepare('SELECT senha_user FROM tb_user WHERE id_user = :id');
        $consultaSenha->bindParam(':id', $idPerfil, PDO::PARAM_INT);
        $consultaSenha->execute();
        $dadosUsuario = $consultaSenha->fetch(PDO::FETCH_ASSOC);

        if (!$dadosUsuario) {
          $mensagemPerfil = 'Não foi possível localizar os dados deste usuário.';
        } else {
          if (!empty($_FILES['foto']['name'])) {
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['png', 'jpg', 'jpeg', 'gif'];

            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK || !in_array($extensao, $extensoesPermitidas, true)) {
              $mensagemPerfil = 'Arquivo inválido. Envie uma imagem PNG, JPG ou GIF.';
            } else {
              $pastaUpload = __DIR__ . '/../../img/user/';
              $fotoNova = uniqid('', true) . '.' . $extensao;
              $arquivoNovo = $pastaUpload . $fotoNova;

              if (!move_uploaded_file($_FILES['foto']['tmp_name'], $arquivoNovo)) {
                $mensagemPerfil = 'Não foi possível fazer o upload da imagem.';
                $arquivoNovo = null;
              }
            }
          }

          if ($mensagemPerfil === '') {
            $senhaAlterada = $senhaNova !== '';
            $senha = $senhaAlterada
              ? password_hash($senhaNova, PASSWORD_DEFAULT)
              : $dadosUsuario['senha_user'];

            $atualizacao = $conect->prepare(
              'UPDATE tb_user SET foto_user = :foto, nome_user = :nome, email_user = :email, senha_user = :senha WHERE id_user = :id'
            );
            $atualizacao->bindParam(':id', $idPerfil, PDO::PARAM_INT);
            $atualizacao->bindParam(':foto', $fotoNova, PDO::PARAM_STR);
            $atualizacao->bindParam(':nome', $nome, PDO::PARAM_STR);
            $atualizacao->bindParam(':email', $email, PDO::PARAM_STR);
            $atualizacao->bindParam(':senha', $senha, PDO::PARAM_STR);
            $atualizacao->execute();

            if ($arquivoNovo && $fotoAntiga !== 'avatar-padrao.png' && basename($fotoAntiga) === $fotoAntiga) {
              $caminhoFotoAntiga = __DIR__ . '/../../img/user/' . $fotoAntiga;
              if (is_file($caminhoFotoAntiga)) {
                unlink($caminhoFotoAntiga);
              }
            }

            if ($email !== $email_user || $senhaAlterada) {
              header('Location: home.php?sair=1');
            } else {
              header('Location: home.php?acao=perfil&atualizado=1');
            }
            exit;
          }
        }
      } catch (PDOException $e) {
        if ($arquivoNovo && is_file($arquivoNovo)) {
          unlink($arquivoNovo);
        }
        error_log('ERRO AO ATUALIZAR PERFIL: ' . $e->getMessage());
        $mensagemPerfil = 'Ocorreu um erro ao atualizar o perfil. Tente novamente.';
      }
    }
  }

  if (isset($_GET['atualizado'])) {
    $mensagemPerfil = 'Perfil atualizado com sucesso.';
    $tipoMensagemPerfil = 'success';
  }
  ?>
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Editar Perfil</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if ($mensagemPerfil !== ''): ?>
          <div class="alert alert-<?php echo $tipoMensagemPerfil; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo htmlspecialchars($mensagemPerfil, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editar Perfil</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" required value="<?php echo $nome_user; ?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Endereço de E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required value="<?php echo $email_user; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Senha</label>
                    <input type="password" class="form-control" name="senha" id="telefone" value="" placeholder="**************************">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Avatar do usuário</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" for="exampleInputFile">Arquivo de imagem</label>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="upPerfil" class="btn btn-primary">Alterar dados do usuário</button>
                </div>
              </form>
             
            </div>
</div>
            
            <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Dados do Usuário</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style="text-align: center; margin-bottom: 98px">
              

                

                <h1><?php echo $nome_user; ?></h1>
                <strong><?php echo $email_user; ?></strong>
                
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
  