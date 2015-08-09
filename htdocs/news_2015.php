<?php

/* next news images:
  A       0       ?           www/htdocs/images/original_size/chrome.png
  A       0       ?           www/htdocs/images/original_size/dragon_squash_plugin.png
  A       0       ?           www/htdocs/images/original_size/firefox.png
  A       0       ?           www/htdocs/images/original_size/fly_over_river_screen_26.png
  A       0       ?           www/htdocs/images/original_size/plugin_4.png
  A       0       ?           www/htdocs/images/original_size/plugin_windows_chrome.png
  A       0       ?           www/htdocs/images/original_size/plugin_windows_ff.png
*/

array_push($news,
    array('title' => 'Castle Game Engine 5.2.0 release',
          'year' => 2015,
          'month' => 7,
          'day' => 5,
          'short_description' => '',
          'guid' => '2015-07-05',
          'description' =>
castle_thumbs(array(
  array('filename' => 'enemies_and_shadows.png', 'titlealt' => 'Enemies and Shadows - screen from Web3d 2015 tutorial'),
  array('filename' => 'texture_memory_profiler_3.png', 'titlealt' => 'Texture memory profiler in action'),
  array('filename' => 'hydra_battles_screen_best.png', 'titlealt' => 'Hydra Battles, an isometric RTS game using Castle Game Engine'),
  array('filename' => 'dragon_squash_title.png', 'titlealt' => 'Dragon Squash - Android game integration with Google Games (title, sign-in)'),
  array('filename' => 'dragon_squash_achievements.png', 'titlealt' => 'Dragon Squash - Android game integration with Google Games (leaderboards)'),
  array('filename' => 'dragon_squash_game_over.png', 'titlealt' => 'Dragon Squash - Game Over screen, with some new font features'),
)) .
'<p>We\'re proud to present new <b>Castle Game Engine release 5.2.0</b>!

<div class="download jumbotron">
<a href="' . CURRENT_URL . 'engine.php" class="btn btn-primary btn-lg">Download Castle Game Engine 5.2.0!</a>
</div>

<p>Be sure to also check out
<a href="http://castle-engine.sourceforge.net/miscella/cge_tutorial_slides.pdf">the slides</a>
and <a href="https://github.com/michaliskambi/cge-tutorial">examples (data and code)</a>
from a tutorial about our engine given during the recent <i>Web3d&nbsp;2015 conference</i>.
They show (from the ground up) the creation of a simple 3D FPS game and 2D game.

<p>The 5.2.0 release brings various improvements to the engine capabilities and API:

<ol>
  <li><p><b>GPU texture memory profiler</b> is now available. This tells you exactly which textures are worth optimizing (maybe scale them down, maybe compress for GPU, maybe remove alpha channel or convert to grayscale...). See <a href="http://castle-engine.sourceforge.net/apidoc/html/CastleGLImages.html#TextureMemoryProfiler">TextureMemoryProfiler</a> documentation.

  <li><p>Many improvements related to <b>texture GPU compression</b> (especially important on mobile platforms, where texture memory is precious):

    <ul>
      <li>New compression formats available: ATITC, PVRTC, ETC (in addition to previous S3TC formats).
      <li>Many more functions now accept GPU-compressed images.
      <li><a href="http://castle-engine.sourceforge.net/apidoc/html/CastleImages.html#LoadImagePreprocess">LoadImagePreprocess</a> allows to replace image URLs at runtime, to switch uncompressed texture files with compressed.
    </ul>
  </li>

  <li><p>New capabilities for <b>font rendering</b>: <b>scaling:</b>
  ' . api_link('TCastleFont.Scale', 'CastleFonts.TCastleFont.html#Scale', false) . ',
  <b>outline:</b>
  ' . api_link('TCastleFont.Outline', 'CastleFonts.TCastleFont.html#Outline', false) . ',
  ' . api_link('TCastleFont.OutlineColor', 'CastleFonts.TCastleFont.html#OutlineColor', false) . ',
  <b>print in rect:</b>
  ' . api_link('TCastleFont.PrintRect', 'CastleFonts.TCastleFont.html#PrintRect', false) . ',
  ' . api_link('TCastleFont.PrintRectMultiline', 'CastleFonts.TCastleFont.html#PrintRectMultiline', false) . ',
  ' . api_link('TCastleFont.PushProperties', 'CastleFonts.TCastleFont.html#PushProperties', false) . ',
  ' . api_link('TCastleFont.PopProperties', 'CastleFonts.TCastleFont.html#PopProperties', false) . '.

  <li><p>' . api_link('TUIState', 'CastleUIState.TUIState.html', false) . ' to help implementing <b>simple UI mode switching (with possible stack)</b>.

  <li><p>New option <code>android_project</code> was added to our <a href="https://sourceforge.net/p/castle-engine/wiki/Build%20tool/">build tool</a> to support custom Java and manifest code in your Android project. This allows to <b>integrate your Android game with Google Games (leaderboards, achievements, save games...), biling, ads, analytics and anything else you want:)</b>

  <li><p><b>Getting and setting fields of X3D nodes</b> has now clean API. Just use <code>LightNode.Intensity := 0.5</code> instead of ugly <code>LightNode.FdIntensity.Send(0.5)</code>.

  <li><p>By default we use fcl-image <b>built-in handling of PNG</b> format. This removes the need to use/distribute extra libraries for handling PNG on any platform.

  <li><p>On <b>Windows GUI applications, we will log to the <code>xxx.log</code> file by default</b>, instead of trying to log to unavailable stderr. <a href="http://castle-engine.sourceforge.net/tutorial_log.php">See the updated tutorial link about logging for details.</a>

  <li><p>' . api_link('TCastle2DControl', 'CastleControl.TCastle2DControl.html', false) . ' component for easily creating 2D games. Already contains a ready T2DSceneManager.

  <li><p><b>Encrypt/decrypt XML data using BlowFish</b>. In the simplest case, just set <code>TCastleConfig.BlowFishKeyPhrase</code> property. This allows to encrypt your XML data, for example to prevent cheaters from easily modifying your game configuration variables in XML. It assumes that the particular value of BlowFishKeyPhrase you use is kept secret &mdash; e.g. it\'s compiled and maybe obfuscated.

  <li><p>Numerous smaller new features:
  ' . api_link('TGLImage.Draw3x1', 'CastleGLImages.TGLImage.html#Draw3x1', false) . ',
  ' . api_link('TGLImage.Rect', 'CastleGLImages.TGLImage.html#Rect', false) . ',
  ' . api_link('TGLImage.ScalingPossible settable', 'CastleGLImages.TGLImage.html#ScalingPossible', false) . ',

  ' . api_link('TCasScriptExpression.AsFloat', 'CastleScript.TCasScriptExpression.html#AsFloat', false) . ',

  ' . api_link('TFramesPerSecond.MaxSensibleSecondsPassed', 'CastleTimeUtils.TFramesPerSecond.html#MaxSensibleSecondsPassed', false) . ',

  ' . api_link('TCastleConfig.GetMultilineText', 'CastleXMLConfig.TCastleConfig.html#GetMultilineText', false) .
  ' (plus a few helpers for ' .
      api_link('TCastleConfig', 'CastleXMLConfig.TCastleConfig.html', false) .
  ' to get/set RGB and RGBA colors),

  ' . api_link('TRectangle.Align', 'CastleRectangles.TRectangle.html#Align', false) . '
   (plus related
  ' . api_link('THorizontalPosition', 'CastleRectangles.html#THorizontalPosition', false) . ',
  ' . api_link('TVerticalPosition', 'CastleRectangles.html#TVerticalPosition', false) . '),

  ' . api_link('TCastleSceneCore.AnimationDuration', 'CastleSceneCore.TCastleSceneCore.html#AnimationDuration', false) . ' .
  </li>
</ol>'),

    array('title' => 'Castle Game Engine 5.1.2 release (fixes to TCastleControl, tutorial, more)',
          'year' => 2015,
          'month' => 1,
          'day' => 11,
          'short_description' => '',
          'guid' => '2015-01-11',
          'description' =>
castle_thumbs(array(
  array('filename' => 'android_emulator.png', 'titlealt' => '&quot;Dragon Spine&quot; running in an Android emulator'),
)) .
'<p>Castle Game Engine version 5.1.2 was just released! This release brings a couple of fixes and improvements to the engine.

<div class="download jumbotron">
<a href="' . CURRENT_URL . 'engine.php" class="btn btn-primary btn-lg">Download Castle Game Engine 5.1.2!</a>
</div>

<ol>
  <li>New useful events are published on <code>TCastleControl</code>:

<pre>
OnOpen: TNotifyEvent
OnClose: TNotifyEvent
OnBeforeRender: TNotifyEvent
OnRender: TNotifyEvent
OnResize: TNotifyEvent
OnPress: TControlInputPressReleaseEvent
OnRelease: TControlInputPressReleaseEvent
OnMotion: TControlInputMotionEvent
OnUpdate: TNotifyEvent
</pre>

     <p>These should be used to watch open/close of context, to watch key/mouse events and to perform continous updates. For detailed documentation of them, see <a href="http://castle-engine.sourceforge.net/apidoc/html/CastleControl.TCastleControlCustom.html">TCastleControlCustom</a> reference.</p>

    <p>Some previously published stuff on <code>TCastleControl</code> is deprecated now and will be removed in next release (sorry, we have to break compatibility &mdash; this old stuff really doesn\'t make much sense, all new code should use new events). Also, the deprecated <code>OnPaint</code> was fixed &mdash; just in case you\'re using it (but please switch to using <code>OnRender</code> soon!).

  <li><p>The beginning of the <a href="http://castle-engine.sourceforge.net/tutorial_intro.php">tutorial</a> was much streamlined. We fixed some wording, removed a lot of useless info, and made the beginning of the tutorial really smooth. Now, you really quickly get to the <i>"I made a 3D game!"</i> stage :)

    <p>Also, the tutorial chapter <a href="http://castle-engine.sourceforge.net/tutorial_castle_scene_transform.php">"Adding a simple moving object" was added</a>.

  <li>Also, as you probaly see, the website got a total facelift, using HTML5 and Bootstrap style. Various pages and menus were rearranged to be more helpful. I hope you enjoy the new website &mdash; please leave a note in the comments :)

  <li>You can save almost 0.7 MB from exe size by undefining CASTLE_EMBED_ALL_3D_FONT_VARIATIONS in some cases, see src/base/castleconf.inc for comments.

  <li>Various testsuite fixes and improvements.

  <li>Improve CastleCurves API, and also mark most of it as deprecated.
</ol>
')
);
