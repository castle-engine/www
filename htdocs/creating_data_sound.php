<?php
require_once 'castle_engine_functions.php';
creating_data_header('Sound');
?>

<p>Below is a sample sound configuration,
with links to documentation for every attribute.
See <?php echo a_href_page('tutorial about sounds', 'tutorial_sound'); ?>
 for information how to initialize sound repository from such XML configuration.
</p>

<?php echo xml_highlight(
'<?xml version="1.0"?>

<sounds>
  <!--
    Contains a list of <sound> elements.
    Only the "name" attribute is required, and all names must be unique.
  -->

  <sound
    [[CastleSoundEngine.TSoundInfo.html#Name|name]]="player_sudden_pain"
    [[CastleSoundEngine.TSoundInfo.html#FileName|file_name]]="test_name.wav"
    [[CastleSoundEngine.TSoundInfo.html#DefaultImportance|default_importance]]="player"
    [[CastleSoundEngine.TSoundInfo.html#Gain|gain]]="1"
    [[CastleSoundEngine.TSoundInfo.html#MinGain|min_gain]]="0.8"
    [[CastleSoundEngine.TSoundInfo.html#MaxGain|max_gain]]="1" />

  <!-- And more <sound> elements... -->
  <sound name="test_sound_1" />
  <sound name="test_sound_2" />
  <sound name="test_sound_3" />
</sounds>'); ?>

------------------------------------------------------------------------------
TODO: old castle-development text, to be simplified


<p>Notes about sound files:

<ul>
  <li><p>Sound file formats: currently our engine can play WAV
    and OggVorbis files. Short sounds should be stored as WAV,
    long sounds (like level music) may be stored as OggVorbis files.

  <li><p>Do not make your sounds more silent
    just because you're recording some "silent" thing.
    For example, <tt>mouse_squeek.wav</tt> should be as loud
    as <tt>plane_engines_starting.wav</tt>. The fact that mouse squeek
    is in reality much more quiet than plane engine doesn't matter here.
    You should always make your sound files with maximum quality,
    and this means that they should use all the available sound range.

  <li><p>Music: as of 2006-04-25, music is done and it's great.
    To create a music I just need a sound file that can be nicely
    played in a loop.

  <li>Special notes for creating footsteps sound:
    <ul>
      <li>Don't make the footsteps sound too long.
        Preferably you should put there only 2 footsteps. Reason ?
        When progress is displayed (e.g. because player changes levels),
        or when player enters the game menu, footsteps sound is not
        immediately stopped &mdash; it's just played until the end.
        Yes, this is desired, as it makes better effect than suddenly
        stopping all the sounds.

      <li>These 2 footsteps should take about 1 second. This is the amount
        of time that "feels good" with head bobbing.
        (See the <tt>data/player.xml</tt> file, <tt>head_bobbing_time = 0.5</tt>
        there means that 1 footstep = 0.5 of the second for head bobbing.)
    </ul>

  <li><p>Remember that if sounds are supposed to be spatialized (i.e. played
    by Sound3d procedures), then you must make them mono (never stereo!).
    That's because Windows OpenAL will never spatialize stereo sounds.

    <p>You can use any editor you like to convert your sounds to mono.
    I like this sox command-line:
    <pre>  sox input.wav -c 1 output.wav</pre>
    See also <tt>data/sounds/scripts/example_make_mono.sh</tt>
</ul>

<?php
creating_data_footer();
?>
