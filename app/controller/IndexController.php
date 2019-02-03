<?php

class IndexController
{
    public function index()
    {
        $view = new View();
        $posts = Post::all();

        $view->render('index', [
            "posts" => $posts
        ]);
    }

    public function view($id = 0)
    {
        $view = new View();

        $view->render('view', [
            "post" => Post::find($id)
        ]);
    }

    public function newPost()
    {
        $data = $this->_validate($_POST);
        $uploaddir = App::config('imagedir');
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        if ($_FILES['userfile']['type'] != 'image/jpeg'){
            echo 'File is not a jpeg image, upload not allowed';
            exit();
        }

        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Upload failed";
            exit();
        }

        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO post (content) VALUES (:content)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }

    /**
     * @param $data
     * @return array|bool
     */
    private function _validate($data)
    {
        $required = ['content'];

        //validate required keys
        foreach ($required as $key) {
            if (!isset($data[$key])) {
                return false;
            }

            $data[$key] = trim((string)$data[$key]);
            if (empty($data[$key])) {
                return false;
            }
        }
        return $data;
    }
}