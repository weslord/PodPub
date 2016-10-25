<?php
  class Edamame {
    protected $db;
    protected $series;
    protected $episodes;

    function __construct($dbpath) {
      $dsn = "sqlite:".$dbpath;
      $this->db = new PDO($dsn); // add error handling...
    }

    public function seriesInfo() {
      $this->series = $this->db->query('SELECT * FROM seriesinfo;')->fetch(PDO::FETCH_ASSOC);
      ?>
        <div id="podpub-series-info">
          <h1><?= $this->series['title']; ?></h1>
          <p><?= $this->series['longdesc']; ?></p>
          <img src="<?= $this->series['imageurl']?>" width="250px" height="250px" />
          <a href="feed.php">RSS feed</a><?php //get from db ?>
        </div>
      <?php
    } // seriesInfo

    public function listEpisodes() {
      $this->episodes = $this->db->query('SELECT * FROM episodes ORDER BY number DESC;');
      ?>
        <div id="podpub-episodes">
          <?php
            // reset pointer?
            while ($episode = $this->episodes->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)) {
          ?>

            <div class="podpub-episode" id="podpub-ep-<?= $episode['number'] ?>">
              <h2 class="podpub-title"><?= $episode['number'] ?> - <?= $episode['title'] ?></h2>
              <span class="podpub-timestamp"><?= date('l F jS, Y', $episode['timestamp']); ?></span>
              <div class="podpub-longdesc"><?= str_replace(['<![CDATA[',']]>'],"",$episode['longdesc']) ?></div>
              <a class="podpub-mediaurl" href="<?= $episode['mediaurl'] ?>">mp3</a>
            </div>

          <?php } ?>

        </div>
      <?php

    } // listEpisodes

    public function adminSeries($formTargetPath = "") {
      if ($_POST['form-type'] == "series") {
        $this->writeSeries();
      }

      $this->series = $this->db->query('SELECT * FROM seriesinfo;')->fetch(PDO::FETCH_ASSOC);
      $series = $this->series;
      include "series-form.inc";
    } // adminSeries
    
    public function adminEpisode($formTargetPath = "") {
      if ($_POST['form-type'] == "episode") {
        $this->writeEpisode();
      }

      $this->series = $this->db->query('SELECT * FROM seriesinfo;')->fetch(PDO::FETCH_ASSOC);
      $series = $this->series;
      $this->episodes = $this->db->query('SELECT * FROM episodes ORDER BY number DESC;');

      $lastepisode = $this->episodes->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
      include "episode-form.inc";
    }

    private function writeEpisode() {
      $seriesupdate = $this->db->prepare("
        INSERT INTO `episodes`  ( number, title, artist, shortdesc, longdesc, mediatype, timestamp, duration)
        VALUES                  (:number,:title,:artist,:shortdesc,:longdesc,:mediatype,:timestamp,:duration);");

      $seriesupdate->execute(array(
        ':number' => $_POST['ep-number'],
        ':title' => $_POST['ep-title'],
        ':artist' => $_POST['ep-artist'],
        ':shortdesc' => $_POST['ep-shortdesc'],
        ':longdesc' => $_POST['ep-longdesc'],
        ':mediatype' => $_POST['ep-mediatype'],
        ':timestamp' => strtotime($_POST['ep-timestamp']),
        ':duration' => $_POST['ep-duration'],
        ));
    }

    public function writeData() {
      if ($_POST['form-type'] == "series") {
        $this->writeSeries();
      } else if ($_POST['form-type'] == "episode") {
        $this->writeEpisode();
      }

    }

    private function writeSeries() {
      $seriesupdate = $this->db->prepare("
        UPDATE `seriesinfo`
        SET `title`=:title,
            `artist`=:artist,
            `copyright`=:copyright,
            `url`=:url,
            `owner`=:owner,
            `email`=:email,
            `shortdesc`=:shortdesc,
            `longdesc`=:longdesc,
            `category`=:category,
            `explicit`=:explicit,
            `language`=:language
        WHERE `_rowid_`='1';");

      $seriesupdate->execute(array(
        ':title' => $_POST['series-title'],
        ':artist' => $_POST['series-artist'],
        ':copyright' => $_POST['series-copyright'],
        ':url' => $_POST['series-url'],
        ':owner' => $_POST['series-owner'],
        ':email' => $_POST['series-email'],
        ':shortdesc' => $_POST['series-shortdesc'],
        ':longdesc' => $_POST['series-longdesc'],
        ':category' => $_POST['series-category'],
        ':explicit' => $_POST['series-explicit'],
        ':language' => $_POST['series-language'],
      ));

      if (!$imagedir) {
        $imagedir = getcwd();
      }

      // check $_FILE for errors, type, etc
      if ($_FILES['series-imageurl']['error'] == UPLOAD_ERR_OK) {
        // save to series cover location
        $imagepath = $imagedir . "/cover.png"; // check for type, set extension
        move_uploaded_file($_FILES['series-imageurl']['tmp_name'],$imagepath);

        // delete/archive existing, if different
      }
    }
    
    public function rss() {

      $series = $this->db->query('SELECT * FROM seriesinfo;')->fetch(PDO::FETCH_ASSOC);
      $episodes = $this->db->query('SELECT * FROM episodes ORDER BY number DESC;');

      include "feed.rss";
    }
  }

?>