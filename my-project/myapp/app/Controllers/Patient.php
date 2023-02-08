<?php

namespace App\Controllers;

/**
 * Class Patient
 * 
 * Responsável por gerenciar o cadastro de pacientes
 */
class Patient extends BaseController
{

    public function __construct()
    {
        $this->patientModel = model('App\Models\PatientModel');
    }

    /**
     * Listagem de pacientes
     * @return \Twig\Display
     */
    public function index()
    {
        $data['patients'] = $this->patientModel->findAll();

        return $this->twig->display('patients/index', $data);
    }

    /**
     * Cadastro de paciente
     * @return \Twig\Display
     */
    public function create()
    {
        $data['form_action'] = base_url(route_to('patient.store'));
        $data['name'] = old('name');
        $data['email'] = old('email');
        $data['phone'] = old('phone');

        return $this->twig->display('patients/form', $data);
    }

    /**
     * Grava paciente na base de dados
     */
    public function store()
    {
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['phone'] = $this->request->getPost('phone');
        if($this->patientModel->insert($data) === false){
            $this->session->setFlashdata('errors', $this->patientModel->errors());

            return redirect()->route('patient.create')->withInput();
        } else {
            $this->session->setFlashdata('success', 'Cadastro realizado com sucesso!');

            return redirect('patient');
        }
    }

    /**
     * Alteração de paciente
     * @param int $id ID do paciente
     * @return \Twig\Display
     */
    public function edit($id)
    {
        $patient = $this->patientModel->find($id);

        $data['form_action'] = base_url(route_to('patient.update', $id));
        $data['name'] = $patient['name'];
        $data['email'] = $patient['email'];
        $data['phone'] = $patient['phone'];

        return $this->twig->display('patients/form', $data);
    }

    /**
     * Salva paciente da base de dados
     * @param int $id ID do paciente
     */
    public function update($id)
    {
        $data['name'] = $this->request->getPost('name');
        $data['email'] = $this->request->getPost('email');
        $data['phone'] = $this->request->getPost('phone');
        if($this->patientModel->update($id, $data) === false){
            $this->session->setFlashdata('errors', $this->patientModel->errors());

            return redirect()->route('patient.edit', $id)->withInput();
        } else {
            $this->session->setFlashdata('success', 'Cadastro salvo com sucesso!');

            return redirect('patient');
        }
    }

    /**
     * Exclusão lógica de paciente
     * @param int $id ID do paciente
     */
    public function delete($id)
    {
        try {
            $this->patientModel->delete($id);

            $this->session->setFlashdata('success', 'Paciente removido com sucesso!');
        } catch(\Exception $e) {
            $this->session->setFlashdata('errors', $e->getMessage());
        }

        return redirect('patient');
    }
}
