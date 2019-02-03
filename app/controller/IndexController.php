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
        $post = Post::find($id);
        if ($post === NULL){
            header("HTTP/1.0 404 Not Found");
            exit();
        }
        $view = new View();
        $comments = Comment::getComments($id);
        $view->render('view', [
            "post" => $post,
            "comments" => $comments
        ]);
    }

    public function newPost()
    {
        $data = $this->_validate($_POST);
        $filename = NULL;
        $uploaddir = App::config('imagedir');
        if ($_FILES['userfile']['type'] != NULL){
            $filename = basename($_FILES['userfile']['name']);
            $uploadfile = $uploaddir . $filename;
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
        }

        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO post (content,image_location) VALUES (:content,:image_location)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->bindValue('image_location', $filename);
            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }
    public function newComment()
    {
        $data = $this->_validate($_POST);
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO comments (content,postid) VALUES (:content,:postid)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->bindValue('postid', $data['postid']);
            $stmt->execute();
            header('Location: ' . App::config('url').'Index/view/'.$data['postid']);
        }

    }

    public function deletePost(){
        $data = $_POST;
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'DELETE FROM post WHERE id = :id';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('id', $data['postid']);
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