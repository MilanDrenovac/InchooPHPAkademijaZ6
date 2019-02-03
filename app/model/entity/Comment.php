<?php

class Comment
{
    private $id;

    private $postid;

    private $content;

    private $time;

    public function __construct($id, $content, $postid, $time)
    {
        $this->setId($id);
        $this->setPostid($postid);
        $this->setContent($content);
        $this->setTime($time);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function __call($name, $arguments)
    {
        $function = substr($name, 0, 3);
        if ($function === 'set') {
            $this->__set(strtolower(substr($name, 3)), $arguments[0]);
            return $this;
        } else if ($function === 'get') {
            return $this->__get(strtolower(substr($name, 3)));
        }

        return $this;
    }

    public static function getComments($postid)
    {
        $list = [];
        $db = Db::connect();
        $statement = $db->prepare("select * from comments where postid = :postid order by id desc ");
        $statement->bindValue('postid', $postid);
        $statement->execute();
        foreach ($statement->fetchAll() as $comment) {
            $list[] = new Comment($comment->id, $comment->content, $comment->postid, $comment->ts);
        }
        return $list;
    }
}