<?php

/* Copyright 2001-2017 Michalis Kamburelis.

   This file is part of "Kambi PHP library".

   "Kambi PHP library" is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   "Kambi PHP library" is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with "Kambi PHP library"; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

/* Table of contents entry, passed to TableOfContents constructor. */
class TocItem {
  /* HTML used to display section name.
     *May* contain inline HTML, but no links (this is already inside a link). */
  var $display_name;

  /* HTML anchor. HTML not allowed (it will be escaped). */
  var $anchor_name;
  var $nesting;
  var $number; //< generated by html_toc() writing

  /* Create new ToC entry.
     Pass $an_anchor_name = NULL (default) to autogenerate based on $a_display_name.
  */
  function __construct($a_display_name, $an_anchor_name = NULL, $a_nesting = 0)
  {
    $this->display_name = $a_display_name;
    if (empty($an_anchor_name)) {
      $this->anchor_name = self::autogenerate_anchor($a_display_name);
    } else {
      $this->anchor_name = $an_anchor_name;
    }
    $this->nesting = $a_nesting;
  }

  private static function autogenerate_anchor($caption)
  {
    /* Replace some characters to underscore.
       Note that this is not necessary strictly for correctness (we will sanitize anchor
       with htmlspecialchars anyway), but it makes anchors look better. */
    $replace_chars = array(' ', '\\', '/', '(', ')');
    return str_replace($replace_chars, '_', $caption);
  }
}

/* Table of contents, defines as an array of TableOfContents, with easy methods to output it. */
class TableOfContents {
  var $items;
  var $next_section_heading;

  /* Show section numbers in html_section */
  var $echo_numbers = true;

  /* Gets $an_items array, must be indexed by integers starting from 0
     (and keys must be sorted by these integers too, so possibly
     you have to use ksort first). */
  function __construct($an_items)
  {
    $this->items = $an_items;
    $this->next_section_heading = 0;
    $this->echo_numbers = true;
  }

  /* private funcs -----------------------------------------------------------  */

  /* Remove from $s last number (delimited by dot). Returns this number. */
  function pop_last_number(&$s)
  {
    $dot_pos = strrpos($s, '.');
    if ($dot_pos !== false)
    {
      /* some dot found, remove dot + all else */
      $result = (int) substr($s, $dot_pos + 1);
      $s = substr($s, 0, $dot_pos);
    } else
    {
       /* no dot, so just remove the string */
      $result = (int) $s;
      $s = '';
    }
    return $result;
  }

  /* Add to $s new last number (delimited by dot). */
  function push_last_number(&$s, $number)
  {
    if ($s == '')
      $s = (string) $number; else
      $s = $s . '.' . ((string) $number);
  }

  /* end of private funcs ---------------------------------------------------  */

  /* Returns a string that contains HTML <ol> item with our
     table of contents. */
  function html_toc()
  {
    $result = '<div class="table_of_contents">';
    $old_nesting = -1;

    /* Since old_nesting = -1, so first item will always enter
       ($now_nesting == $old_nesting + 1) branch, and will start the
       first <ol> and will initialize $current_number to '1'. */
    $current_number = '';

    foreach($this->items as $toc_item_key => $toc_item)
    {
      $now_nesting = $toc_item->nesting;

      $list_item =
        "<li><a href=\"#section_" . htmlspecialchars($toc_item->anchor_name) .
          "\">" . $toc_item->display_name . "</a>\n";

      if ($now_nesting == $old_nesting + 1)
      {
        $result .= "<ol class=\"list-no-margin\">\n" . $list_item;
        $this->push_last_number($current_number, 1);
      } else
      if ($now_nesting == $old_nesting)
      {
        $result .= "</li>\n" . $list_item;
        $num = $this->pop_last_number($current_number);
        $this->push_last_number($current_number, $num + 1);
      } else
      if ($now_nesting < $old_nesting)
      {
        $result .= "</li>\n";
        for ($temp_for = 0; $temp_for < $old_nesting - $now_nesting; $temp_for++)
        {
          $result .= "</ol>\n</li>\n";
          $this->pop_last_number($current_number);
        }
        $result .= $list_item;
        $num = $this->pop_last_number($current_number);
        $this->push_last_number($current_number, $num + 1);
      } else
      {
        exit('Incorrect toc items nesting: nesting of consecutive items must differ at most by 1');
      }

      /* This doesn't work on SourceForge (does nothing), possibly related
         to PHP 4:

            $toc_item->number = $current_number;

         Foreach operates on copy of the array, although I would think that
         only $toc_item reference is copied. Anyway, PHP 5 has syntax

           foreach($this->items as &$toc_item)

         (ampersand before $toc_item) but this is not for PHP 4.
         Hack below works always, as we explicitly index original array: */
      $this->items[$toc_item_key]->number = $current_number;

      $old_nesting = $now_nesting;
    }

    while ($old_nesting >= 0)
    {
      $result .= "</li>\n</ol>\n";
      $old_nesting--;
    }

    $result .= '</div>';

    return $result;
  }

  function html_section()
  {
    $toc_item = $this->items[$this->next_section_heading];

    /* Our $toc_item->nesting is always from 0.
       For HTML, <h1> should be taken by page title, so our nesting
       0 corresponds to <h2>. */
    $heading_level = min($toc_item->nesting + 2, 6);

    $result = "<h$heading_level id=\"section_" .
      htmlspecialchars($toc_item->anchor_name) . "\">" .
      ($this->echo_numbers ? $toc_item->number . '. ' : '') .
      $toc_item->display_name . "</h$heading_level>\n";
    $this->next_section_heading++;
    return $result;
  }
}

?>
