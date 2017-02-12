<?php
  class BlogPost {

    private $ownid;
    private $filename;
    private $title;
    private $author;
    private $date_val;
    private $content;

      function _construct($ownid, $filename, $title, $author, $date_val, $content) {
          $this->$ownid    = $ownid;
          $this->$filename = $filename;
          $this->$title    = $title;
          $this->$author   = $author;
          $this->$date_val = $date_val;
          $this->$content  = $content;
      }

      public function getTitle() {
        return $title;
      }

      public function getAuthor() {
        return $author;
      }

      public function getContent() {
        return $content;
      }

      public function getDate() {
        return $date_val;
      }
  }
?>
