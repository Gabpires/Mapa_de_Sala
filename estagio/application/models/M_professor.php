<?php
defined("BASEPATH") or exit("No direct script access allowed");

class M_professor extends CI_Model
{

    public function inserir($nome, $cpf, $tipo)
    {
        try {
            $retornoConsulta = $this->consultaProfessorCpf($cpf);

            if ($retornoConsulta['codigo'] != 9 && $retornoConsulta['codigo'] != 10) {
                $this->db->query(" insert into tbl_professor (nome, cpf, tipo) values ('$nome', '$cpf', '$tipo' )");

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Professor cadastrado corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Erro ao cadastrar professor.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => $retornoConsulta['codigo'],
                    'msg' => $retornoConsulta['msg']
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro foi encontrado: ' . $e->getMessage()
            );
        }
        return $dados;

    }

    private function consultaProfessorCpf($cpf)
    {
        try {
            $sql = "select * from tbl_professor where cpf = '$cpf'";

            $retornoProfessor = $this->db->query($sql);

            if ($retornoProfessor->num_rows() > 0) {
                $linha = $retornoProfessor->row();
                if (trim($linha->estatus) == 'D') {
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Professor encontrado, porém está desativado, fale com o adm.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 10,
                        'msg' => 'Professor já cadastrado no sistema.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg' => 'Professor não encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro foi encontrado: ' . $e->getMessage()
            );
        }
        return $dados;
    }

    private function consultaProfessorCod($codigo)
    {
        try {
            $sql = "select * from tbl_professor where codigo = '$codigo' and estatus = ''";

            $retornoProfessor = $this->db->query($sql);

            if ($retornoProfessor->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.'
                );
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg' => 'Professor não encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro foi encontrado: ' . $e->getMessage()
            );
        }
        return $dados;
    }

    public function consultar($codigo, $cpf, $nome, $tipo)
    {
        try {
            $sql = "select * from tbl_professor where estatus = ''";

            if (trim($codigo) != '') {
                $sql .= " and codigo = '$codigo'";
            }

            if (trim($cpf) != '') {
                $sql .= " and cpf = '$cpf'";
            }

            if (trim($nome) != '') {
                $sql .= " and nome like '%$nome%'";
            }

            if (trim($tipo) != '') {
                $sql .= " and tipo = '$tipo'";
            }

            $sql = $sql . " order by nome ";

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 11,
                    'msg' => 'Nenhum professor encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro foi encontrado: ' . $e->getMessage()
            );
        }
        return $dados;
    }

    public function alterar($codigo, $nome, $cpf, $tipo)
    {
        try {
            $retornoConsulta = $this->consultaProfessorCod($codigo);

            if ($retornoConsulta['codigo'] == 1) {
                $query = "update tbl_professor set ";

                if ($nome !== '') {
                    $query .= " nome = '$nome', ";
                }

                if ($cpf !== "") {
                    $query .= " cpf = '$cpf', ";
                }

                if ($tipo !== "") {
                    $query .= " tipo = '$tipo', ";
                }

                $queryFinal = rtrim($query, ", ") . " where codigo = '$codigo'";

                $this->db->query($queryFinal);

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Professor alterado corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Nenhuma alteração foi realizada.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => $retornoConsulta['codigo'],
                    'msg' => $retornoConsulta['msg']
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro foi encontrado: ' . $e->getMessage()
            );
        }
        return $dados;
    }

    public function desativar($codigo)
    {
        try {
            $retornoConsulta = $this->consultaProfessorCod($codigo);

            if ($retornoConsulta['codigo'] == 1) {
                $this->db->query("update tbl_professor set estatus = 'D' where codigo = '$codigo'");

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Professor desativado corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 5,
                        'msg' => 'Erro ao desativar professor.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'professor não encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro foi encontrado: ' . $e->getMessage()
            );
        }
        return $dados;
    }
}