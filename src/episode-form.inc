<div>
  <h2>Add Episode</h2>
  <form enctype="multipart/form-data" method="post" action="<?= $formTargetPath ?>">
    <input type="hidden" name="form-type" value="episode">
    <label>Episode #
      <input name="ep-number" type="text" value="<?= $lastepisode['number']+1; ?>"/></label><br>
    <label>Episode title:
      <input name="ep-title" type="text" value="<?= $series['title']." Episode ".($lastepisode['number']+1); ?>"/></label><br>
    <label>Artist:
      <input name="ep-artist" type="text" value="<?= $series['artist']; ?>"/></label><br>
    <label>Short description:
      <input name="ep-shortdesc" type="text" value="<?= $series['shortdesc']; ?>"/></label><br>
    <label>Long description:<br>
      <textarea name="ep-longdesc" placeholder="<?= $series['shortdesc']?>"><?= $series['longdesc']?></textarea>
    </label><br>
    <label>Cover image<br>
      <input name="ep-imageurl" type="file" accept="image/*"/><br>
      <img width='250px' height='250px' src='<?= $series['imageurl'] ?>' />
    </label><br>
    <label>MP3 File:<br>
      <input name="ep-mediaurl" type="file" accept="audio/mpeg"/>
    </label><br>
    <label>Timestamp: (Weekday Month Day, Year)
      <input name="ep-timestamp" type="text" value="<?= date('l F jS, Y'); //['timestamp'] ?>"/></label><br>
    <label>Length: (HH:MM:SS)
      <input name="ep-duration" type="text" value="<?php //['duration'] ?>"/>
    </label><br>
    <input type="submit" />
  </form>
</div>