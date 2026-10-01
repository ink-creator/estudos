  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Relatório de Contatos</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Lista de contatos</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Foto</th>
                  <th>Nome</th>
                  <th>Telefone</th>
                  <th>E-mail</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include_once('../config/conexao.php');

                $select = "SELECT * FROM tb_contatos WHERE id_user = :id_user ORDER BY id_contatos DESC";

                try {
                    $result = $conect->prepare($select);
                    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                    $result->execute();

                    $cont = 1;
                    while ($show = $result->fetch(PDO::FETCH_OBJ)) {
                        $foto = $show->foto_contatos;
                        $caminhoFoto = '../img/cont/' . $foto;

                        if ($foto == 'avatar-padrao.png' || empty($foto) || !file_exists(__DIR__ . '/../../img/cont/' . $foto)) {
                            $src = '../img/avatar_p/avatar-padrao.png';
                        } else {
                            $src = '../img/cont/' . $foto;
                        }
                ?>
                        <tr>
                          <td><?php echo $cont++; ?></td>
                          <td>
                            <img src="<?php echo $src; ?>" alt="Foto do contato" title="Foto do contato" style="width: 50px; border-radius: 100%;">
                          </td>
                          <td><?php echo $show->nome_contatos; ?></td>
                          <td><?php echo $show->fone_contatos; ?></td>
                          <td><?php echo $show->email_contatos; ?></td>
                          <td>
                            <div class="btn-group">
                              <a href="home.php?acao=editar&id=<?php echo $show->id_contatos; ?>" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></a>
                              <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos; ?>" onclick="return confirm('Deseja remover o contato')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
                            </div>
                          </td>
                        </tr>
                <?php
                    }
                } catch (PDOException $e) {
                    echo '<tr><td colspan="6">Erro ao carregar contatos: ' . $e->getMessage() . '</td></tr>';
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Foto</th>
                  <th>Nome</th>
                  <th>Telefone</th>
                  <th>E-mail</th>
                  <th>Ações</th>
                </tr>
              </tfoot>
            </table>

            <div class="col-lg-12 d-flex justify-content-center">
              <a href="conteudo/relatoriopdf.php?id=<?php echo $id_user; ?>" class="btn btn-lg btn-primary">Gerar relatório completo</a>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
