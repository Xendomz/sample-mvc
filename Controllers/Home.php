<?php
    class Home extends Controllers{
        public function __construct()
        {
            parent::__construct();
        }

        public function home($params) 
        {
            $this->views->getView($this, 'Home/home', [
                'page_title' => "Mahasiswa",
                'data' => $this->model('Mahasiswa')->get()
            ]);
        }

        public function create()
        {
            $this->views->getView($this, 'Home/createHome', [
                'page_title' => 'Mahasiswa'
            ]);
        }

        public function store()
        {
            $this->model('Mahasiswa')->insert([
                'nama' => $_POST['nama'],
                'nim' => $_POST['nim'],
                'alamat' => $_POST['alamat'],
            ]);

            header('Location:'. base_url().'/home');
        }

        public function edit($param)
        {
            $this->views->getView($this, 'Home/editHome', [
                'page_title' => 'Mahasiswa',
                'data' => $this->model('Mahasiswa')->getWhereOnce(['id', '=', $param])
            ]);
        }

        public function update($param)
        {
            $this->model('Mahasiswa')->update([
                'nama' => $_POST['nama'],
                'nim' => $_POST['nim'],
                'alamat' => $_POST['alamat'],
            ], ['id', '=', $param]);

            header('Location:'. base_url().'/home');
        }

        public function delete($param)
        {
            $this->model('Mahasiswa')->delete(['id', '=', $param]);
            
            header('Location:'. base_url().'/home');
        }
    }













        // public function create()
        // {
        //     $this->views->getView($this, 'createHome', [
        //         'page_title' => 'Mahasiswa'
        //     ]);
        // }

        // public function store()
        // {
        //     $this->model('Mahasiswa')->insert([
        //         'nama' => $_POST['nama'],
        //         'nim' => $_POST['nim'],
        //         'alamat' => $_POST['alamat'],
        //     ]);

        //     header('Location:'. base_url().'/home');
        // }

        // public function edit($param)
        // {
        //     $this->views->getView($this, 'editHome', [
        //         'page_title' => 'Mahasiswa',
        //         'data' => $this->model('Mahasiswa')->getWhereOnce(['id', '=', $param])
        //     ]);
        // }

        // public function update($param)
        // {
        //     $this->model('Mahasiswa')->update([
        //         'nama' => $_POST['nama'],
        //         'nim' => $_POST['nim'],
        //         'alamat' => $_POST['alamat'],
        //     ], ['id', '=', $param]);

        //     header('Location:'. base_url().'/home');
        // }

        // public function delete($param)
        // {
        //     $this->model('Mahasiswa')->delete(['id', '=', $param]);
            
        //     header('Location:'. base_url().'/home');
        // }
    // }