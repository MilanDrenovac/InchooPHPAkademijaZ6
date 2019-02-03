<?php

class Post
{
    private $id;

    private $content;

    private $imageloc;

    public function __construct($id, $content, $imageloc, $time, $commentCount)
    {
        $this->setId($id);
        $this->setContent($content);
        $this->setImageloc($imageloc);
        $this->setTime($time);
        $this->setCommentcount($commentCount);
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

    public static function all()
    {
        $list = [];
        $db = Db::connect();
        $statement = $db->prepare("select *, (select count(*) from comments where comments.postid=post.id) as comment_count from post order by id desc");
        $statement->execute();
        foreach ($statement->fetchAll() as $post) {
            $list[] = new Post($post->id, $post->content, $post->image_location, $post->ts,$post->comment_count);
        }
        return $list;
    }

    public static function find($id)
    {
        $id = intval($id);
        $db = Db::connect();
        $statement = $db->prepare("select * from post where id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $post = $statement->fetch();
        if ($post == NULL){
            return NULL;
        }
        return new Post($post->id, $post->content, $post->image_location, $post->ts,0);
    }
}