<?php

namespace App\Message;

use App\Entity\Post;

class PostToDistribute
{
    private Post $post;

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
