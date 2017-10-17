<?php
  class BlogPost {

    function __construct(
                  $ownid,
                  $title,
                  $author,
                  $email,
                  $releasedate,
                  $content)
    {
      $this->ownid = $ownid;
      $this->title = $title;
      $this->author = $author;
      $this->email = $email;
      $this->releasedate = $releasedate;
      $this->content = $content;
    }

    function getOwnId() {
      return $this->ownid;
    }

    function getTitle() {
      return $this->title;
    }

    function getAuthor() {
      return $this->author;
    }

    function getEmail() {
      return $this->email;
    }

    function getReleaseDate() {
      return date("d.m.Y", strtotime($this->releasedate));
    }

    function getContent() {
      return $this->content;
    }
  }
?>
