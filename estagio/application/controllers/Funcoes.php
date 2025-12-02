<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funcoes extends CI_Controller {

    public function index() {
        $this->load->view('login');
    }

    public function indexPagina() {
        $this->load->view('Index');
    }

    public function encerraSistema() {
        header('location: ' . base_url());
    }

    public function abreSala() {
        $this->load->view('Sala');
    }

    public function abreProfessor() {
        $this->load->view('Professor');
    }

    public function abreTurma() {
        $this->load->view('Turma');
    }

    public function abrePeriodo() {
        $this->load->view('Periodo');
    }

    public function abreMapa() {
        $this->load->view('Mapa');
    }

    public function abreRelatorio() {
        $this->load->view('Relatorio');
    }
}