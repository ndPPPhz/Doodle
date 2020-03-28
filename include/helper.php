<?php
/**
*
* Get times as option-list.
*
* @return string List of times
*/

function get_times( $default , $interval = '+30 minutes' ) {
  $output = '';
  $current = strtotime( '00:00' );
  $end = strtotime( '23:59' );
  while( $current <= $end ) {
      $time = date( 'H:i', $current );
      $sel = ( $time == $default ) ? ' selected' : '';

      $output .= "<option value=\"{$time}\"{$sel}>" . date( 'h.i A', $current ) .'</option>';
      $current = strtotime( $interval, $current );
  }
  return $output;
}
?>