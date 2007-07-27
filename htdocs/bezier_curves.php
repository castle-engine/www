<?php
  require_once 'vrmlengine_functions.php';

  common_header("bezier_curves", LANG_EN,
    "bezier_curves - plot rational bezier curves.");
?>

<?php
  echo pretty_heading("bezier_curves", VERSION_BEZIER_CURVES);
  echo default_medium_image_progs_demo("bezier_curves");
?>

<p>Plot rational Bezier curves and smooth interpolated curves (Bezier
curves connected smoothly).

<p>These are binaries of the program. No special installation is required,
just unpack the archive and run <tt>bezier_curves</tt>.
<?php echo_standard_program_download('bezier_curves', 'bezier_curves',
  VERSION_BEZIER_CURVES, true); ?>

<p><?php echo SOURCES_OF_THIS_PROG_ARE_AVAIL; ?>

<p>Documentation below is in somewhat raw state, sorry.

<p>Controlling with mouse:

<dl>
  <dt>Left mouse click
  <dd>If none curve selected,
     create new curve with one point (so use backspace to start creating
       new curve), else
     add point to selected curve after selected point.

  <dt>Middle mouse click (or left mouse click with Shift down)
  <dd>Select closest control point (and select it's curve).

  <dt>Dragging with right mouse button down
  <dd>If ctrl is not down, then you move selected point.
      Else you move whole selected curve.
</dl>

<p>For a whole lifetime, you can have one selected curve and one selected
point on that curve.

<p>On start background is inited from
UserOptionsPath + <tt>default_bezier_curves_image.jpg</tt>, if it exists.
UserOptionsPath = (linux, freebsd, macosx) home dir, (win32) exe dir.

<p>Save/Open work using non-standard nodes RationalBezierCurve
and SmoothInterpolatedCurve (defined in unit VRMLBezierCurve).

<pre>
RationalBezierCurve {
  SFFloat tBegin 0.0             # must be tBegin &lt;= tEnd
  SFFloat tEnd 1.0
  MFVec3f controlPoints [0 0 0]  # must always have Length &gt;= 1
  MFFloat weights [1]            # must have Length = controlPoints.Length
}

SmoothInterpolatedCurve {
  SFFloat tBegin 0.0             # must be tBegin &lt;= tEnd
  SFFloat tEnd 1.0
  MFVec3f controlPoints [0 0 0]  # must always have Length &gt;= 1
}
</pre>

<h2><a name="section_depends">Requirements</a></h2>

<?php echo depends_ul( array(
  DEPENDS_OPENGL,
  DEPENDS_LIBPNG_AND_ZLIB,
  DEPENDS_UNIX_GLWINDOW_GTK_2,
  DEPENDS_MACOSX) );
?>

<?php
  if (!IS_GEN_LOCAL) {
    $counter = php_counter("bezier_curves", TRUE);
  };

  common_footer();
?>