<div id="edamame-episodes">
  <h2>
    Episodes
  </h2>
  <?php
    // reset pointer?
    while ($episode = $this->episodes->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)) {
  ?>

    <div class="edamame-episode" id="edamame-ep-<?= $episode['id'] ?>">
      <h3 class="edamame-title"><a href="?episode=<?= $episode['permalink'] ?>">
      <?php 
        if ($episode['season']) {
          echo $episode['season'] . '-';
        }
      ?><?= $episode['number'] ?> - <?= $episode['title'] ?></a></h3>
      <span class="edamame-timestamp"><?= date('l F jS, Y', $episode['timestamp']); ?></span>
      <div class="edamame-longdesc"><?= str_replace(["\r\n","\n","\r"],"<br />", $episode['longdesc']) ?></div>
      <?php if ($episode['imagefile']) { ?> 
        <div class="edamame-imagefile">
          <img src="<?= $mediafolder['mediafolder'] . $episode['imagefile']; ?>" width="200px" height="200px"></img>
        </div>
      <?php } ?>
      <audio class="edamame-preview" src="<?= $mediafolder['mediafolder'] . $episode['mediafile'] ?>" preload="none" controls></audio>
      <a class="edamame-mediaurl" href="<?= $mediafolder['mediafolder'] . $episode['mediafile'] ?>">mp3</a>
      <?php
        if ($this->verified) {
          ?>
          <form enctype="multipart/form-data" method="post" action="">
            <input type="hidden" name="delete-episode" value="<?= $episode['id'] ?>">
            <input type="submit" value="Delete Episode"/>
          </form>
          <?php
        }
      ?>
    </div>

  <?php } ?>

</div>